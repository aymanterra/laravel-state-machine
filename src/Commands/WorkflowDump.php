<?php

namespace troojaan\SM\Commands;

use Illuminate\Console\Command;
use Exception;
use Symfony\Component\Process\Process;
use Symfony\Component\Workflow\Definition;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Dumper\GraphvizDumper;
use Symfony\Component\Workflow\Workflow;

class WorkflowDump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflow:dump
        {workflow : name of workflow from configuration}
        {--format=png : the image format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GraphvizDumper dumps a workflow as a graphviz file.
        You can convert the generated dot file with the dot utility (http://www.graphviz.org/):';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $workflowName   = $this->argument('workflow');
        $format         = $this->option('format');
        $config         = config('state-machine');

        if (!isset($config[$workflowName])) {
            throw new Exception("Workflow $workflowName is not configured.");
        }

        $original_transitions = config('state-machine')[$workflowName]['transitions'];
        $transitions = [];
        foreach ($original_transitions as $key => $original_transition) {
            $transition = new Transition($key, $original_transition['from'], $original_transition['to']);
            array_push($transitions, $transition);
        }
        $states = array_keys(config('state-machine')[$workflowName]['states']);
        $definition = new Definition($states, $transitions, 'new');
        $workflow   = new Workflow($definition);
        $definition = $workflow->getDefinition();
        $dumper = new GraphvizDumper();
        $dotCommand = "dot -T$format -o $workflowName.$format";
        $process = new Process($dotCommand);
        $process->setInput($dumper->dump($definition));
        $process->mustRun();
    }
}

<?php

return [
    'simple' => [
        // class of your domain object
        'class' => App\Order::class,

        // name of the graph (default is "default")
        'graph' => 'simple',

        // property of your object holding the actual state (default is "state")
        'property_path' => 'state',

        // list of all possible states
        'states' => [
            'new',
            'pending_review',
            'awaiting_changes',
            'accepted',
            'rejected',
            'published',
        ],

        // list of all possible transitions
        'transitions' => [
            'create' => [
                'from' => ['new'],
                'to' => ['pending_review'],
            ],
            'ask_for_changes' => [
                'from' => ['pending_review', 'accepted'],
                'to' => ['awaiting_changes'],
                // to make the from states dependent on each other
                // (cant proceed unless if all from states are in the current states)
                // 'dependent' =>  true,
            ],
            'cancel_changes' => [
                'from' => ['awaiting_changes'],
                'to' => ['pending_review'],
            ],
            'submit_changes' => [
                'from' => ['awaiting_changes'],
                'to' => ['pending_review'],
            ],
            'approve' => [
                'from' => ['pending_review', 'rejected'],
                'to' => ['accepted'],
            ],
            'publish' => [
                'from' => ['accepted'],
                'to' => ['published'],
            ],
        ],

        'callbacks' => [

            'guard' => [
                'guard_on_creating' => [
                    'on' => ['create'],
                    'do' => 'App\your-directory\CreateCallback@fire',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'guard_on_ask_for_changes' => [
                    'on' => ['ask_for_changes'],
                    'do' => 'App\your-directory\AskForChangesCallback@fire',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'guard_on_cancel_changes' => [
                    'on' => ['cancel_changes'],
                    'do' => 'App\your-directory\CancelChangesCallback@fire',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'guard_on_submitting' => [
                    'on' => ['submit_changes'],
                    'do' => 'App\your-directory\SubmitChangesCallback@fire',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'guard_on_approving' => [
                    'on' => ['approve'],
                    'do' => 'App\your-directory\ApproveCallback@fire',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'guard_on_publishing' => [
                    'on' => ['publish'],
                    'do' => 'App\your-directory\PublishCallback@fire',
                    'args' => ['object', 'event', '"simple"'],
                ],
            ],

            // will be called before applying a transition
            'before' => [],
            // will be called after applying a transition
            'after' => [
                'after_creating' => [
                    'on' => ['create'],
                    'do' => 'App\your-directory\CreateCallback@addHistory',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'after_ask_for_changes' => [
                    'on' => ['ask_for_changes'],
                    'do' => 'App\your-directory\AskForChangesCallback@addHistory',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'after_cancel_changes' => [
                    'on' => ['cancel_changes'],
                    'do' => 'App\your-directory\CancelChangesCallback@addHistory',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'after_submitting' => [
                    'on' => ['submit_changes'],
                    'do' => 'App\your-directory\SubmitChangesCallback@addHistory',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'after_approving' => [
                    'on' => ['approve'],
                    'do' => 'App\your-directory\ApproveCallback@addHistory',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'after_publishing' => [
                    'on' => ['publish'],
                    'do' => 'App\your-directory\PublishCallback@addHistory',
                    'args' => ['object', 'event', '"simple"'],
                ],
            ],
        ],
    ],
    'orders2Dir' => [
        // class of your domain object
        'class' => App\Order::class,

        // name of the graph (default is "default")
        'graph' => 'orders2DirDepend',

        // property of your object holding the actual state (default is "state")
        'property_path' => 'state',

        // list of all possible states
        // each state have to contain array of array called 'splitted_by'
        // splitted_by is array of all transition's names that split and lead to this state
        // exp:     S1--t1-->S2----|--t2-->S4
        //           |--t1-->S3    |--t2-->S5
        //      S1 splitted_by none
        //      S2 splitted_by t1
        //      S3 splitted_by t1
        //      S4 splitted_by t1, t2
        //      S5 splitted_by t1, t2
        'states' => [
            'new' => [
                'splitted_by' => []
            ],
            'waiting_HR_approval' => [
                'splitted_by' => ['resignation_request']
            ],
            'exit_enterview_done' => [
                'splitted_by' => ['resignation_request']
            ],
            'waiting_docs_in_branch' => [
                'splitted_by' => ['resignation_request', 'prepare_docs']
            ],
            'waiting_docs_in_main_office' => [
                'splitted_by' => ['resignation_request', 'prepare_docs']
            ],
            'docs_delivered_in_branch' => [
                'splitted_by' => ['resignation_request', 'prepare_docs']
            ],
            'docs_delivered_in_main_office' => [
                'splitted_by' => ['resignation_request', 'prepare_docs']
            ],
            'waiting_HR_manager_approval' => [
                'splitted_by' => ['resignation_request']
            ],
            'HR_approved' => [
                'splitted_by' => ['resignation_request']
            ],
            'waiting_IT_approval' => [
                'splitted_by' => ['resignation_request']
            ],
            'email_disabled_and_wait_IT_manager' => [
                'splitted_by' => ['resignation_request']
            ],
            'IT_approved' => [
                'splitted_by' => ['resignation_request']
            ],
            'waiting_accountant_approval' => [
                'splitted_by' => ['resignation_request']
            ],
            'waiting_purchasing_dep_approval' => [
                'splitted_by' => ['resignation_request', 'end_finance_relation']
            ],
            'purchasing_dep_approved' => [
                'splitted_by' => ['resignation_request', 'end_finance_relation']
            ],
            'waiting_bank_account_closing' => [
                'splitted_by' => ['resignation_request', 'end_finance_relation']
            ],
            'bank_account_closed' => [
                'splitted_by' => ['resignation_request', 'end_finance_relation']
            ],
            'waiting_personal_loans_closing' => [
                'splitted_by' => ['resignation_request', 'end_finance_relation']
            ],
            'waiting_personal_loans_closing_indep_1' => [
                'splitted_by' => ['resignation_request', 'end_finance_relation', 'check_personal_loans']
            ],
            'waiting_personal_loans_closing_indep_1_1' => [
                'splitted_by' => ['resignation_request', 'end_finance_relation', 'check_personal_loans']
            ],
            'waiting_personal_loans_closing_indep_1_2' => [
                'splitted_by' => ['resignation_request', 'end_finance_relation', 'check_personal_loans']
            ],
            'waiting_personal_loans_closing_indep_2' => [
                'splitted_by' => ['resignation_request', 'end_finance_relation', 'check_personal_loans']
            ],
            'waiting_personal_loans_closing_indep_2_1' => [
                'splitted_by' => ['resignation_request', 'end_finance_relation', 'check_personal_loans']
            ],
            'waiting_personal_loans_closing_indep_3' => [
                'splitted_by' => ['resignation_request', 'end_finance_relation', 'check_personal_loans']
            ],
            'waiting_personal_loans_closing_indep_3_1' => [
                'splitted_by' => ['resignation_request', 'end_finance_relation', 'check_personal_loans']
            ],
            'personal_loans_closed' => [
                'splitted_by' => ['resignation_request', 'end_finance_relation']
            ],
            'accountant_approved' => [
                'splitted_by' => ['resignation_request']
            ],
            'CEO_approved' => [
                'splitted_by' => []
            ],
        ],

        // list of all possible transitions
        'transitions' => [
            'resignation_request' => [
                'from' => ['new'],
                // when splitting to multiple state you have to add this transition name in all later states
                // in splitted_by array till merging done, and if this splitting will be merged all splitted
                // branches must be merged with each other
                //   exp: split to 3 branches so when merging must merge from this 3 branches in order all
                // branching to be the same type for all branches (dependent, independent)
                'to' => ['waiting_HR_approval', 'waiting_IT_approval', 'waiting_accountant_approval'],
            ],
            'exit_enterview' => [
                'from' => ['waiting_HR_approval'],
                'to' =>  ['exit_enterview_done'],
            ],
            'prepare_docs' => [
                'from' => ['exit_enterview_done'],
                'to' =>  ['waiting_docs_in_branch', 'waiting_docs_in_main_office'],
            ],
            'deliver_docs_in_branch' => [
                'from' => ['waiting_docs_in_branch'],
                'to' =>  ['docs_delivered_in_branch'],
            ],
            'deliver_docs_in_main_office' => [
                'from' => ['waiting_docs_in_main_office'],
                'to' =>  ['docs_delivered_in_main_office'],
            ],
            'HR_deliver_all_docs' => [
                'from' => ['docs_delivered_in_main_office', 'docs_delivered_in_branch'],
                'to' =>  ['waiting_HR_manager_approval'],
                // while merging from multiple states to one state you have to define if this states are dependent
                // of each other or not
                //  dependent: (this transition wouldn't be available unless all the 'from states'
                //      are in current states)
                //      exp: the final approval of the manager needs approval from 2 different departments
                //  independent: (means that this transition will be available if one or more 'from states'
                //      are in current states
                //      exp: the final approval of the manager needs at least one approval from 2 different departments
                'dependent' =>  false,
                // if it is independent you have to define to transition which split this branch
                'branch_transition' =>  'prepare_docs',
            ],
            'HR_manager_approval' => [
                'from' => ['waiting_HR_manager_approval'],
                'to' =>  ['HR_approved'],
            ],
            'disable_email_account' => [
                'from' => ['waiting_IT_approval'],
                'to' =>  ['email_disabled_and_wait_IT_manager'],
            ],
            'IT_manager_approval' => [
                'from' => ['email_disabled_and_wait_IT_manager'],
                'to' =>  ['IT_approved'],
            ],
            'end_finance_relation' => [
                'from' => ['waiting_accountant_approval'],
                'to' =>  ['waiting_purchasing_dep_approval', 'waiting_bank_account_closing', 'waiting_personal_loans_closing'],
            ],
            'no_pending_purchasing' => [
                'from' => ['waiting_purchasing_dep_approval'],
                'to' =>  ['purchasing_dep_approved'],
            ],
            'bank_account_closed' => [
                'from' => ['waiting_bank_account_closing'],
                'to' =>  ['bank_account_closed'],
            ],
            'check_personal_loans' => [
                'from' => ['waiting_personal_loans_closing'],
                'to' =>  ['waiting_personal_loans_closing_indep_1', 'waiting_personal_loans_closing_indep_2', 'waiting_personal_loans_closing_indep_3'],
            ],
            'personal_loans_closing_indep_1_to_1_1' => [
                'from' => ['waiting_personal_loans_closing_indep_1'],
                'to' =>  ['waiting_personal_loans_closing_indep_1_1'],
            ],
            'personal_loans_closing_indep_1_1_to_1_2' => [
                'from' => ['waiting_personal_loans_closing_indep_1_1'],
                'to' =>  ['waiting_personal_loans_closing_indep_1_2'],
            ],
            'personal_loans_closing_indep_2_to_2_1' => [
                'from' => ['waiting_personal_loans_closing_indep_2'],
                'to' =>  ['waiting_personal_loans_closing_indep_2_1'],
            ],
            'personal_loans_closing_indep_3_to_3_1' => [
                'from' => ['waiting_personal_loans_closing_indep_3'],
                'to' =>  ['waiting_personal_loans_closing_indep_3_1'],
            ],
            'close_personal_loans' => [
                'from' => ['waiting_personal_loans_closing_indep_1_2', 'waiting_personal_loans_closing_indep_2_1', 'waiting_personal_loans_closing_indep_3_1'],
                'to' =>  ['personal_loans_closed'],
                'dependent' =>  false,
                'branch_transition' =>  'check_personal_loans',
            ],
            'accountant_manager_approval' => [
                'from' => ['purchasing_dep_approved', 'bank_account_closed', 'personal_loans_closed'],
                'to' =>  ['accountant_approved'],
                'dependent' =>  true,
            ],
            'CEO_final_approval' => [
                'from' => ['HR_approved' ,'IT_approved' , 'accountant_approved'],
                'to' =>  ['CEO_approved'],
                'dependent' =>  true,
            ],
        ],

        // list of all callbacks
        'callbacks' => [
            // will be called when testing a transition
//            'guard' => [
//                'guard_on_submitting' => [
//                    // call the callback on a specific transition
//                    'on' => 'submit_changes',
//                    // will call the method of this class
//                    'do' => ['MyClass', 'handle'],
//                    // arguments for the callback
//                    'args' => ['object'],
//                ],
//            ],

            // will be called before applying a transition
            'before' => [],

            // will be called after applying a transition
            'after' => [],
        ],
    ],

];

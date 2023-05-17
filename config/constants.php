<?php

return [
    'finance_operation' => [
        'status' => [
            'error'     => -1,
            'waiting'   => 0,
            'paid'      => 1,
        ], 
        'payment_system' => [
            'score'         => 0,
            'payture'       => 1,
            'site'          => 2,
        ],     
    ],
    'ip_white_list' => [
        '127.0.0.1',
        '192.168.0.32',
        '213.159.214.110',
    ],
    'agents' => [
        'url' => env('TAMTEM_AGENTS_URL', 'http://127.0.0.1:8000'),
				'agent-id-from-inn' => env('TAMTEM_AGENTS_AGENT_ID_FROM_INN_API_URL', 'https://agent.tamtem.ru/api/v1/tamtem/agentidfrominn'),
				'agent-id-from-innn' => env('TAMTEM_AGENTS_AGENT_ID_FROM_INN_API_URL2', 'https://agent.tamtem.ru/api/v1/tamtem/agentidfrominnn'),
    ],
];

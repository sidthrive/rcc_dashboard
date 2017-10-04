<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Username extends CI_Model{
    
    function __construct() {
        parent::__construct();
    }
    
    private $enum = [
        "Fielddinda"=>[
            "Dinda1"=>true,
            "Dinda2"=>true,
            "Dinda3"=>true,
            "Dinda4"=>true,
            "Dinda5"=>true,
            "Dinda6"=>true,
            "Dinda7"=>true,
            "Dinda8"=>true
        ],
        "Fielddika"=>[
            "Dika1"=>true,
            "Dika2"=>true,
            "Dika3"=>true,
            "Dika4"=>true,
            "Dika5"=>true,
            "Dika6"=>true,
            "Dika7"=>true,
            "Dika8"=>true
        ],
        "Reconstra1"=>[
            "Dinda1"=>true,
            "Dinda2"=>true,
            "Dinda3"=>true,
            "Dinda4"=>true,
            "Dinda5"=>true,
            "Dinda6"=>true,
            "Dinda7"=>true,
            "Dinda8"=>true,
            "Dika1"=>true,
            "Dika2"=>true,
            "Dika3"=>true,
            "Dika4"=>true,
            "Dika5"=>true,
            "Dika6"=>true,
            "Dika7"=>true,
            "Dika8"=>true
        ],
        "Reconstra2"=>[
            "Dinda1"=>true,
            "Dinda2"=>true,
            "Dinda3"=>true,
            "Dinda4"=>true,
            "Dinda5"=>true,
            "Dinda6"=>true,
            "Dinda7"=>true,
            "Dinda8"=>true,
            "Dika1"=>true,
            "Dika2"=>true,
            "Dika3"=>true,
            "Dika4"=>true,
            "Dika5"=>true,
            "Dika6"=>true,
            "Dika7"=>true,
            "Dika8"=>true
        ],
        "admin"=>[
            "Dinda1"=>true,
            "Dinda2"=>true,
            "Dinda3"=>true,
            "Dinda4"=>true,
            "Dinda5"=>true,
            "Dinda6"=>true,
            "Dinda7"=>true,
            "Dinda8"=>true,
            "Dika1"=>true,
            "Dika2"=>true,
            "Dika3"=>true,
            "Dika4"=>true,
            "Dika5"=>true,
            "Dika6"=>true,
            "Dika7"=>true,
            "Dika8"=>true
        ],
        "sid"=>[
            "Dinda1"=>true,
            "Dinda2"=>true,
            "Dinda3"=>true,
            "Dinda4"=>true,
            "Dinda5"=>true,
            "Dinda6"=>true,
            "Dinda7"=>true,
            "Dinda8"=>true,
            "Dika1"=>true,
            "Dika2"=>true,
            "Dika3"=>true,
            "Dika4"=>true,
            "Dika5"=>true,
            "Dika6"=>true,
            "Dika7"=>true,
            "Dika8"=>true,
            "demo5"=>true,
            "rccdemo"=>true
        ],
        "FCdemo"=>[
            "rccdemo"=>true
        ]
    ];
    
    private $key = [
        "Fielddinda"=>[
            "Dinda1",
            "Dinda8"
        ],
        "Fielddika"=>[
            "Dika1",
            "Dika8"
        ],
        "Reconstra1"=>[
            "Dika1",
            "Dinda8"
        ],
        "Reconstra2"=>[
            "Dika1",
            "Dinda8"
        ],
        "admin"=>[
            "Dika1",
            "Dinda8"
        ],
        "sid"=>[
            "demo5",
            "rccdemo"
        ],
        "FCdemo"=>[
            "rccdemo",
            "rccdemo"
        ]
    ];


    public function getEnum($fc){
        return $this->enum[$fc];
    }
    
    public function getKey($fc){
        return $this->key[$fc];
    }
}
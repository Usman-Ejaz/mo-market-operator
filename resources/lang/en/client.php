<?php

return [

    /*
    | ======================================================
    |       Client Mapping Keys for Form Registration
    | ======================================================
    */

    'approved' => 'Approved',
    'pending' => 'Pending',

    /* Register Types */
    'registration_types' => [
        'market_participant' => 'Market Participant',
        'service_provider' => 'Service Provider',
    ],

    'categories' => [
        'market_participant' => [
            'generator' => 'Generator',
            'base_supplier' => 'Base Supplier',
            'pakistani_trader' => 'Pakistani Trader',
            'bpc' => 'BPC',
            'captive_generator' => 'Captive Generator',
            'competitive_supplier' => 'Competitive Supplier',
            'international_trader' => 'International Trader'
        ],
        'service_provider' => [
            'transmission_service_provider' => 'Transmission Service Provider',
            'distribution_service_provider' => 'Distribution Service Provider',
            'metering_service_provider' => 'Metering Service Provider'
        ]
    ],

    'general_keys' => [
        'general_01' => 'Power of Attorney of the persons signing the Application',
        'general_02' => 'In case the Applicant is a juridic person, copy of its registration as a company according to the laws of Pakistan',
        'general_03' => 'In case the Applicant is a physical person, copy of its National Identity Card',
        'general_04' => 'Identification of the Bank Account which will be used to settle the market transactions',
        'general_05' => 'Certification issued the Bank in which the Applicant’s Bank Account is opened stating that such account is fully operative, and it is not blocked, intervened or seized',
        'general_06' => 'A signed Declaration of Conformity, duly signed by the Applicant, in the form indicated in Annex 2',
        'general_07' => 'Copy of its NTN from FBR',
        'general_08' => 'Copy of its GST Registration Certificate'
    ],

    'keys' => [
        'generator' => [
            'generator_01' => 'Copy of the License or authorization issued by the Authority',
            'generator_02' => 'Copy of the Connection Agreement with the Transmission or Distribution Licensee, as it corresponds',
            'generator_03' => 'List of all Generation Units or Generation Plants, as the case may be, owned or operated by the Applicant. [The Generation Units or Plants shall be labelled according to the Identification Codes agreed with the System Operator]',
            'generator_04' => 'List of all Metering Points through which the Applicant is performing energy or capacity transactions or intend to perform such transactions. [The Metering Points shall be labelled according to the Identification Codes agreed with the MSP]',
            'generator_05' => 'Certification issued by the Transmission or Distribution Licensee stating that each Connection Point is equipped with a Metering Equipment in compliance with the Grid Code, Commercial Code or Distribution Code, as the case may be',
            'generator_06' => 'Certification issued by the MSP stating that the Meter or Meters installed at each Connection Point have been incorporated into its Secured Metering System',
            // 'generator_07' => [
            //     'title' => 'If applicable, Certification issued by the System Operator or Distribution Licensee, as it corresponds, stating that:',
            //     '1' => 'That the Applicant is equipped, at each Generation Unit or Generation Plant location, with a communication equipment required to communicate with the System Operator, according with the requirements established in the Grid or Distribution Code.',
            //     '2' => 'The Generation Units or Generation Plants, as the case may be, are capable to operate under centralized dispatch instructions issued by the System Operator; or that such capability is not required, according with the prescriptions of the Grid Code.'
            // ],
            'generator_07' => "If applicable, Certification issued by the System Operator or Distribution Licensee, as it corresponds, stating that:\n a. That the Applicant is equipped, at each Generation Unit or Generation Plant location, with a communication equipment required to communicate with the System Operator, according with the requirements established in the Grid or Distribution Code.\n b. The Generation Units or Generation Plants, as the case may be, are capable to operate under centralized dispatch instructions issued by the System Operator; or that such capability is not required, according with the prescriptions of the Grid Code",

        ],
        'base_supplier' => [    // base_supplier and competitive_supplier are the same.
            'base_supplier_01' => 'Copy of the License issued by the Authority which permits the Applicant to operate as a Supplier',
            'base_supplier_02' => 'Copy of the Connection Agreement with the Transmission or Distribution Licensee, as it corresponds, if such Connection Agreements are required',
            'base_supplier_03' => 'List of all Metering Points through which the Applicant is performing energy or capacity transactions or intend to perform such transactions. [The Metering Points shall be labelled according to the Identification Codes agreed with the MSP]',
            'base_supplier_04' => 'Certification issued by the Transmission or Distribution Licensee stating that each Connection Point is equipped with a Metering Equipment in compliance with the Grid Code, Commercial Code or Distribution Code, as the case may be',
            'base_supplier_05' => 'Certification issued by the MSP stating that the Meter or Meters installed at each Connection Point have been incorporated into its Secured Metering System',
            'base_supplier_06' => 'Copy of the Use of System Agreement signed by the Applicant and the Transmission or Distribution Licensee, as the case may be'
        ],
        'pakistani_trader' => [
            'paki_trader_01' => 'Copy of the License issued by the Authority which permits the Applicant to operate as a Trader',
            'paki_trader_02' => 'Copy of the Connection Agreement with the Transmission or Distribution Licensee, as it corresponds, if such Connection Agreements are required',
            'paki_trader_03' => 'List of all Metering Points through which the Applicant is performing energy or capacity transactions or intend to perform such transactions. [The Metering Points shall be labelled according to the Identification Codes agreed with the MSP]',
            'paki_trader_04' => 'Certification issued by the Transmission or Distribution Licensee stating that each Connection Point is equipped with a Metering Equipment in compliance with the Grid Code, Commercial Code or Distribution Code, as the case may be',
            'paki_trader_05' => 'Certification issued by the MSP stating that the Meter or Meters installed at each Connection Point have been incorporated into its Secured Metering System',
            'paki_trader_06' => 'Certification issued by the System Operator stating that the Applicant is equipped with appropriate communication equipment required to communicate with the System Operator, according with the requirements established in the Grid or Distribution Code'
        ],
        'bpc' => [
            'bpc_01' => 'Copy of the Connection Agreement with the Transmission or Distribution Licensee, as the case may be',
            'bpc_02' => 'List of all Metering Points through which the Applicant is performing energy or capacity transactions or intend to perform such transactions. [The Metering Points shall be labelled according to the Identification Codes agreed with the MSP]',
            'bpc_03' => 'Certification issued by the Metering Service Provider stating that each Connection Point is equipped with a Metering Equipment in compliance with the Grid Code, Commercial Code or Distribution Code, as the case may be',
            'bpc_04' => 'Certification issued by the MSP stating that the Meter or Meters installed at each Connection Point have been incorporated into its Secured Metering System',
            'bpc_05' => 'Certification issued by the System Operator stating that the Applicant is equipped with appropriate communication equipment required to communicate with the System Operator, in case such requirement is established in the Grid Code or Distribution Code'
        ],
        'captive_generator' => [
            'cap_generator_01' => 'Copy of the Connection Agreement with the Transmission or Distribution Licensee, as it corresponds',
            'cap_generator_02' => 'List of all Captive Generation Units or Captive Generation Plants, as the case may be, owned or operated by the Applicant. [The Generation Units or Plants shall be labelled according to the Identification Codes agreed with the System Operator]',
            'cap_generator_03' => 'Where the Market Participant is selling Energy or transporting it for the purpose of consumption by it at some other location',
            'cap_generator_04' => 'List of all Metering Points through which the Applicant is performing or intend to perform energy or capacity transactions. [The Metering Points shall be labelled according to the Identification Codes agreed with the MSP]',
            'cap_generator_05' => 'Certification issued by the Transmission or Distribution Licensee stating that each Connection Point is equipped with a Metering Equipment in compliance with the Grid Code, Commercial Code or Distribution Code, as the case may be',
            'cap_generator_06' => 'Certification issued by the MSP stating that the Meter or Meters installed at each Connection Point have been incorporated into its Secure Metering System',
            'cap_generator_07' => "If applicable, Certification issued by the System Operator or Distribution Licensee, as it corresponds, stating that:\n a. That the Applicant is equipped, at each Generation Unit or Generation Plant location, with a communication equipment required to communicate with the System Operator, according with the requirements established in the Grid or Distribution Code.\n b. The Generation Units or Generation Plants, as the case may be, are capable to operate under centralized dispatch instructions issued by the System Operator; or that such capability is not required, according with the prescriptions of the Grid Code"
        ],
        'competitive_supplier' => [     // base_supplier and competitive_supplier are the same.
            'comp_supplier_01' => 'Copy of the License issued by the Authority which permits the Applicant to operate as a Supplier',
            'comp_supplier_02' => 'Copy of the Connection Agreement with the Transmission or Distribution Licensee, as it corresponds, if such Connection Agreements are required',
            'comp_supplier_03' => 'List of all Metering Points through which the Applicant is performing energy or capacity transactions or intend to perform such transactions. [The Metering Points shall be labelled according to the Identification Codes agreed with the MSP]',
            'comp_supplier_04' => 'Certification issued by the Transmission or Distribution Licensee stating that each Connection Point is equipped with a Metering Equipment in compliance with the Grid Code, Commercial Code or Distribution Code, as the case may be',
            'comp_supplier_05' => 'Certification issued by the MSP stating that the Meter or Meters installed at each Connection Point have been incorporated into its Secured Metering System',
            'comp_supplier_06' => 'Copy of the Use of System Agreement signed by the Applicant and the Transmission or Distribution Licensee, as the case may be'
        ],
        'international_trader' => [
            'int_trader_01' => 'Copy of the License issued by the Authority which permits the Applicant to operate as a Trader',
            'int_trader_02' => 'Copy of the Connection Agreement with the Transmission or Distribution Licensee, as it corresponds, if such Connection Agreements are required',
            'int_trader_03' => 'List of all Metering Points through which the Applicant is performing energy or capacity transactions or intend to perform such transactions. [The Metering Points shall be labelled according the Identification Codes agreed with the MSP]',
            'int_trader_04' => 'Certification issued by the Transmission or Distribution Licensee stating that each Connection Point is equipped with a Metering Equipment in compliance with the Grid Code, Commercial Code or Distribution Code, as the case may be',
            'int_trader_05' => 'Certification issued by the MSP stating that the Meter or Meters installed at each Connection Point have been incorporated into its Secured Metering System',
            'int_trader_06' => 'Certification issued by the System Operator stating that the Applicant is equipped with appropriate communication equipment required to communicate with the System Operator, according with the requirements established in the Grid or Distribution Code'
        ],
        'transmission_service_provider' => [
            'trans_service_pro_01' => 'Copy of the License issued by the Authority',
            'trans_service_pro_02' => 'Identification of the Bank Account which will be used for charges or payments made by the Market Operator',
            'trans_service_pro_03' => 'List of all Metering Points connected at its boundaries where Energy and Capacity shall be measured',
            'trans_service_pro_04' => 'Certification stating that each Connection Point is equipped with a Metering Equipment in compliance with the Grid Code or Distribution Code, as the case may be',
            'trans_service_pro_05' => 'Certification issued by the relevant MSP stating that the Meter or Meters installed at each new Connection Point have been incorporated into its Secured Metering System'
        ],
        'distribution_service_provider' => [
            'dist_service_pro_01' => 'Copy of the License issued by the Authority',
            'dist_service_pro_02' => 'Identification of the Bank Account which will be used for charges or payments made by the Market Operator',
            'dist_service_pro_03' => 'List of all Metering Points connected at its boundaries where Energy and Capacity shall be measured',
            'dist_service_pro_04' => 'Certification stating that each Connection Point is equipped with a Metering Equipment in compliance with the Grid Code or Distribution Code, as the case may be',
            'dist_service_pro_05' => 'Certification issued by the relevant MSP stating that the Meter or Meters installed at each new Connection Point have been incorporated into its Secured Metering System'
        ],
        'metering_service_provider' => [
            'meter_service_pro_01' => 'Copy of its registration with the Authority, allowing it to provide services as Metering Service Provider',
            'meter_service_pro_02' => 'List of all Metering Points for which the Metering Service Provider provide or intends to provide metering services',
            'meter_service_pro_03' => 'Certification issued by the relevant Transmission and Distribution Licensees that stating that each Metering Point is equipped with a Metering Equipment in compliance with the Grid Code or Distribution Code, as the case may be',
            'meter_service_pro_04' => 'Certification stating that for all Metering Points for which the Metering Service Provider provide or intends to provide metering services have been incorporated into its Secured Metering System'
        ]
    ]
];
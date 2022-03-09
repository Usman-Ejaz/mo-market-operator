<?php

return [
    [
        "name" => "users",
        "display_name" => "Users",
        "capabilities" => [
            "list" => "List",
            //"view" => "View",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete"
        ]
    ],
    [
        "name" => "roles",
        "display_name" => "Roles",
        "capabilities" => [
            "list" => "List",
            //"view" => "View",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete"
        ]
    ],
    [
        "name" => "permissions",
        "display_name" => "Permissions",
        "capabilities" => [
            "view" => "View",
            "edit" => "Edit",
        ]
    ],
    [
        "name" => "news",
        "display_name" => "News",
        "capabilities" => [
            "list" => "List",
            //"view" => "View",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
            "publish" => "Publish"
        ]
    ],
    [
        "name" => "jobs",
        "display_name" => "Jobs",
        "capabilities" => [
            "list" => "List",
            //"view" => "View",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
            "publish" => "Publish",
            "view_applications" => "View Applications",
            "export_applications" => "Export Applications",
            "view_job_application" => "View Job Application",
            "delete_job_application" => "Delete Job Application",
        ]
    ],
    // [
    //     "name" => "applications",
    //     "display_name" => "Applications of job",
    //     "capabilities" => [
    //         "view" => "View",
    //         "delete" => "Delete",
    //     ]
    // ],
    [
        "name" => "pages",
        "display_name" => "Pages",
        "capabilities" => [
            "list" => "List",
            //"view" => "View",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
            "publish" => "Publish"
        ]
    ],
    [
        "name" => "faq-categories",
        "display_name" => "Faq Categories",
        "capabilities" => [
            "list" => "List",
            // "view" => "View",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
        ]
    ],
    [
        "name" => "faqs",
        "display_name" => "FAQs",
        "capabilities" => [
            "list" => "List",
            //"view" => "View",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
            "publish" => "Publish"
        ]
    ],
    [
        "name" => "document-categories",
        "display_name" => "Document Categories",
        "capabilities" => [
            "list" => "List",
            // "view" => "View",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
        ]
    ],
    [
        "name" => "documents",
        "display_name" => "Documents",
        "capabilities" => [
            "list" => "List",
            "create" => "Create",
            "edit" => "Edit",
            "publish" => "Publish",
            "delete" => "Delete",
        ]
    ],
    [
        "name" => "menus",
        "display_name" => "Menus",
        "capabilities" => [
            "list" => "List",
            //"view" => "View",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
            "submenus" => "SubMenus"
        ]
    ],    
    [
        "name" => "settings",
        "display_name" => "Site Configurations",
        "capabilities" => [
            "list" => "List"
        ]
    ],
    [
        "name" => "newsletters",
        "display_name" => "Newsletters",
        "capabilities" => [
            "list" => "List",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
            "sendNewsLetter" => "Send Newsletter",
        ]
    ],
    [
        "name" => "subscribers",
        "display_name" => "Subscribers",
        "capabilities" => [
            "list" => "List",
            // "view" => "View",
            // "delete" => "Delete",
            "subscribe" => "Subscribe",
        ]
    ],    
    [
        "name" => "contact-page-queries",
        "display_name" => "Contact Page Queries",
        "capabilities" => [
            "list" => "List",
            "view" => "View",
            "delete" => "Delete",
        ]
    ],
    [
        "name" => "search-statistics",
        "display_name" => "Search Statistics",
        "capabilities" => [
            "list" => "List",
            // "view" => "View",
            // "delete" => "Delete",
            "export_keywords" => "Export Keywords"
        ]
    ],
    [
        "name" => "knowledge-base",
        "display_name" => "Chatbot Knowledge Base",
        "capabilities" => [
            "list" => "List",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
            // "view" => "View",
            // "publish" => "Publish"
        ]
    ],
    [
        "name" => "clients",
        "display_name" => "Clients",
        "capabilities" => [
            "list" => "List",
            "delete" => "Delete",
            "view" => "View"
        ]
    ],
];

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
        "name" => "roles_and_permissions",
        "display_name" => "Roles & Permissions",
        "capabilities" => [
            "list" => "List",
            //"view" => "View",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
            "view_permission" => "View Permissions",
            "edit_permission" => "Edit Permissions",
        ]
    ],
    // [
    //     "name" => "permissions",
    //     "display_name" => "Permissions",
    //     "capabilities" => [
    //         "view" => "View",
    //         "edit" => "Edit",
    //     ]
    // ],
    [
        "name" => "posts",
        "display_name" => "Posts",
        "capabilities" => [
            "list" => "List",
            "view" => "View",
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
    [
        "name" => "pages",
        "display_name" => "CMS Pages",
        "capabilities" => [
            "list" => "List",
            "view" => "View",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
            "publish" => "Publish"
        ]
    ],
    [
        "name" => "faq_categories",
        "display_name" => "FAQ Categories",
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
        "name" => "document_categories",
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
        "name" => "contact_page_queries",
        "display_name" => "Contact Page Queries",
        "capabilities" => [
            "list" => "List",
            "view" => "View",
            "delete" => "Delete",
        ]
    ],
    [
        "name" => "search_statistics",
        "display_name" => "Search Statistics",
        "capabilities" => [
            "list" => "List",
            // "view" => "View",
            // "delete" => "Delete",
            "export_keywords" => "Export Keywords"
        ]
    ],
    [
        "name" => "knowledge_base",
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
    [
        "name" => "static_block",
        "display_name" => "Static Block",
        "capabilities" => [
            "list" => "List",
            // "create" => "Create",
            "edit" => "Edit",
            // "delete" => "Delete",
            // "view" => "View"
        ]
    ],
    [
        "name" => "media_library",
        "display_name" => "Media Library",
        "capabilities" => [
            "list" => "List",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
            // "view" => "View",
            "manage_files" => "Manage Files"
        ]
    ],
    [
        "name" => "slider_images",
        "display_name" => "Slider Images",
        "capabilities" => [
            "list" => "List",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
        ]
    ],
    [
        "name" => "slider_settings",
        "display_name" => "Slider Settings",
        "capabilities" => [
            "list" => "List",
            // "create" => "Create",
            "edit" => "Edit",
            // "delete" => "Delete",
        ]
    ],
    [
        "name" => "our_teams",
        "display_name" => "Our Team",
        "capabilities" => [
            "list" => "List",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
        ]
    ],
    [
        "name" => "team_members",
        "display_name" => "Team Members",
        "capabilities" => [
            "list" => "List",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
        ]
    ],
    [
        "name" => "trainings",
        "display_name" => "Trainings",
        "capabilities" => [
            "list" => "List",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
        ]
    ],
    [
        "name" => "broken_links",
        "display_name" => "Broken Links",
        "capabilities" => [
            "list" => "List",
            "view" => "View",
            "edit" => "Edit",
            "delete" => "Delete",
        ]
    ],
    [
        "name" => "chatbot_feedback",
        "display_name" => "Chatbot Feedback",
        "capabilities" => [
            "list" => "List",
            "view" => "View",
            // "edit" => "Edit",
            "delete" => "Delete",
        ]
    ],

    [
        "name" => "mo-data",
        "display_name" => "Mo Data",
        "capabilities" => [
            "list" => "List",
            "edit" => "Edit",
        ]
    ],

    [
        "name" => "reports",
        "display_name" => "Reports",
        "capabilities" => [
            "list" => "List",
            "edit" => "Edit",
            "delete" => "Delete",
            'create' => "Create",
        ],
    ],
];

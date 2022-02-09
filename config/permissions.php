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
            "export_applications" => "Export Applications"
        ]
    ],
    [
        "name" => "applications",
        "display_name" => "Applications",
        "capabilities" => [
            "view" => "View",
            "delete" => "Delete",
        ]
    ],
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
        "name" => "documents",
        "display_name" => "Documents",
        "capabilities" => [
            "list" => "List",
            "create" => "Create",
            "edit" => "Edit",
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
    ],    [
        "name" => "settings",
        "display_name" => "Settings",
        "capabilities" => [
            "list" => "List"
        ]
    ],
    [
        "name" => "newsletters",
        "display_name" => "News Letters",
        "capabilities" => [
            "list" => "List",
            "create" => "Create",
            "edit" => "Edit",
            "delete" => "Delete",
            "sendNewsLetter" => "Send Newsletter",
        ]
    ],

];

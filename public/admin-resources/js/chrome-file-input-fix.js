/* This is a fix for a Chrome behavior. 
When a file is already selected in file inputs 
and user tries to select another file but cancels, 
the previously selected file is removed from selection. 
This is not a reasoanablly expected behavior as it works
 fine on other browsers. */

/* This fix uses jquery, so include this file after jquery script. And also depends on some ES2015 features*/

let allFilesMap = new Map();

$(document).on('focusin', 'input[type="file"]', function (event) {
    allFilesMap.set(event.target, event.target.files);
});

$(document).on('change', 'input[type="file"]', function (event) {
    if (event.target.files.length === 0) {
        if (allFilesMap.has(event.target)) {
            event.preventDefault();
            event.target.files = allFilesMap.get(event.target);
            console.log("We had previous files");
        }
    }
});

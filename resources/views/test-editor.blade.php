
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="./editor/editor.css" />
    <link rel="stylesheet" href="./editor/style.css" />
</head>
<body>
	<div class="my-editor"></div>

    <script type="module">
      import { appendDefaultEditor } from "./editor/editor.js";

      const pintura = appendDefaultEditor(".my-editor", {
        // The source image to load
        src: "./favicon.png",

        // This will set a square crop aspect ratio
        imageCropAspectRatio: 1,

        // Stickers available to user
        stickers: [
          ["Emoji", ["⭐️", "😊", "👍", "👎", "☀️", "🌤", "🌥"]],
          [
            "Markers",
            [
              {
                src: "sticker-one.svg",
                width: "5%",
                alt: "One"
              },
              {
                src: "sticker-two.svg",
                width: "5%",
                alt: "Two"
              },
              {
                src: "sticker-three.svg",
                width: "5%",
                alt: "Three"
              }
            ]
          ]
        ],
        // updateImage: function () {
        //     console.log(args);
        // }
      });
    </script>
</body>
</html>
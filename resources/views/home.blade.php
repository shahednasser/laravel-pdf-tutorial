<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PDF Maker</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init({
      selector: '.editor',
      toolbar: 
        'styleselect | alignleft aligncenter alignright | bold italic underline strikethrough | image | bullist numlist | table',
      plugins: 'image lists table',
      automatic_uploads: true,
      file_picker_types: 'image',
      file_picker_callback: function (cb, value, meta) {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        input.onchange = function () {
          var file = this.files[0];

          var reader = new FileReader();
          reader.onload = function () {
            var id = 'blobid' + (new Date()).getTime();
            var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
            var base64 = reader.result.split(',')[1];
            var blobInfo = blobCache.create(id, file, base64);
            blobCache.add(blobInfo);
            cb(blobInfo.blobUri(), { title: file.name });
          };
          reader.readAsDataURL(file);
        };

        input.click();
      },
    });
  </script>
</head>
<body>
  <div class="container my-4 mx-auto">
    <form action="/create" method="POST">
      @csrf
      <h1>PDF Maker</h1>
      <h2>Configurations</h2>
      <div class="mb-3">
        <label for="name" class="form-label">PDF Name</label>
        <input type="text" class="form-control" id="name" name="name" />
      </div>
      <div class="mb-3">
        <label for="header" class="form-label">Header</label>
        <textarea name="header" id="header" class="editor"></textarea>
      </div>
      <div class="mb-3">
        <label for="footer" class="form-label">Footer</label>
        <div id="footerHelp" class="form-text">You can use {PAGENO} to add page numbering to all pages</div>
        <textarea name="footer" id="footer" class="editor"></textarea>
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="show_toc" name="show_toc">
        <label class="form-check-label" for="show_toc">
          Show Table of Content?
        </label>
      </div>
      <h2>Content</h2>
      <textarea name="content" id="content" class="editor"></textarea>
      <div class="text-center mt-3">
        <button type="submit" class="btn btn-primary">Create</button>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
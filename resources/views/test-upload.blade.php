<!DOCTYPE html>
<html>
<head>
    <title>Test File Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .success { color: green; margin-top: 10px; }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>
    <h2>Test File Upload (Max 5MB)</h2>

    <form id="uploadForm" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" id="file" accept="image/*">
        <button type="submit">Upload</button>
    </form>

    <div id="result"></div>

    <script>
        document.getElementById('uploadForm').onsubmit = async (e) => {
            e.preventDefault();
            const fileInput = document.getElementById('file');

            if (!fileInput.files.length) {
                document.getElementById('result').innerHTML = '<div class="error">❌ Please choose a file first.</div>';
                return;
            }

            const formData = new FormData();
            formData.append('file', fileInput.files[0]);

            const response = await fetch('/test-upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();
            if (result.success) {
                document.getElementById('result').innerHTML = 
                    '<div class="success">✅ Upload successful! Size: ' + result.size + ' bytes</div>';
            } else {
                document.getElementById('result').innerHTML = 
                    '<div class="error">❌ Upload failed</div>';
            }
        };
    </script>
</body>
</html>

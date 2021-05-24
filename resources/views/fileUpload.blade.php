<!DOCTYPE html>

<body>
    <div class="container mt-5 text-center">
        <h2 class="mb-4">
            Upload CSV file for Data Updation
        </h2>
        <form action='/api/upload' method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                <div class="custom-file text-left">
                    <input type="file" name="csvfile" class="custom-file-input" id="csvfile">

                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-save">  Save</button>
        </form>
    </div>
</body>
</html>

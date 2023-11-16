<!DOCTYPE html>
<html>
<head>
    <title>Category Table</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <table id="categoryTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Category ID</th>
                <th>Category Name</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>sample</td>
                <td>sample</td>
                <td>sample</td>
                <td>sample</td>
            </tr>
        </tbody>
    </table>


    <script>
        $(document).ready(function() {
            var categoryTable = $('#categoryTable').DataTable({
                "ajax": {
                    url: 'api/get_categories.php',
                    type: 'GET',
                    dataType: 'json',
                    dataSrc: '', // Use an empty string since the data array is not wrapped in a property
                },
                columns: [
                    { data: 'category_id' },
                    { data: 'category_name' },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="editBtn">Edit</button>';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="deleteBtn">Delete</button>';
                        }
                    }
                ]
            });
        });
        console.log()
    </script>
</body>
</html>

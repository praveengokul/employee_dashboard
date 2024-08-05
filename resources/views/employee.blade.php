<!DOCTYPE html>
<html>
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.dataTables.min.css" /> -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<head>
    <style>
    .list {
        float: right;
        padding-bottom: 30px;
    }

    .btn-file {
        padding: 10px 30px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        color: #fff;
        font-size: 14px;
        background-color: #4a7d47;
    }

    .main_sec {
        width: 80%;
        margin: auto;
        padding-top: 50px;
    }

    .main_table table.dataTable thead tr th {
        background-color: #f5f4f9;
        padding: 10px 16px;
        border: 1px solid #ededed29;
        font-size: 14px;
        line-height: 21px;
        font-weight: normal;
        color: #1b1f1b;
        font-family: var(--semibold-font);
        white-space: nowrap;
        text-align: left;
    }

    .main_table table.dataTable tbody tr td,
    .main_table table.dataTable.display>tbody>tr.odd>.sorting_1,
    .main_table table.dataTable.display>tbody>tr.even>.sorting_1 {
        background-color: #fff;
        padding: 10px 16px;
        box-shadow: none;
        font: 14px / 28px var(--regular-font);
        color: #1b1f1b;
        font-weight: 400;
        white-space: nowrap;
    }
    .hidden{
        display: none;
    }
    .logout-btn {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 1000;
    }
    </style>
</head>

<body>
    <div class="main_sec">
        <!-- Logout Button -->
        <button id="logoutBtn" class="btn btn-danger logout-btn">Logout</button>
        <div class="main_table hostelListTable">

            <div class="academic_text">
                <p class="user_total"></p>
                <!-- Add Employee Button -->
                <button id="addEmployeeBtn" class="btn btn-primary">Add Employee</button>
            </div>

            <table class="display data--table no-footer dataTable EmployeeList" style="width: 100%;">
                <thead>
                    <tr>
                        <th>User Id</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Date of Birth</th>
                        <th>Date of Join</th>
                        <th class='action_btn'>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <!-- Add Employee Modal -->
    <div id="addEmployeeModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addEmployeeForm">
                        <div class="form-group">
                            <label for="emp_id">Employee ID</label>
                            <input type="text" class="form-control" id="emp_id" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" required>
                        </div>
                        <div class="form-group">
                            <label for="doj">Date of Joining</label>
                            <input type="date" class="form-control" id="doj" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Employee</h4>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editUserId" name="userId">
                        <div class="form-group">
                            <label for="editName">Name:</label>
                            <input type="text" class="form-control" id="editName" name="name">
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email:</label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                        <div class="form-group">
                            <label for="editDob">Date of Birth:</label>
                            <input type="date" class="form-control" id="editDob" name="dob">
                        </div>
                        <div class="form-group">
                            <label for="editDoj">Date of Join:</label>
                            <input type="date" class="form-control" id="editDoj" name="doj">
                        </div>
                        <button type="button" class="btn btn-primary" id="updateBtn">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script>
        const token = localStorage.getItem('userToken');
        if (!token) {
            window.location.href = "/login";
        }
    $(document).ready(function() {
        // Show Add Employee Modal
        $('#addEmployeeBtn').click(function() {
            $('#addEmployeeModal').modal('show');
        });
        var table = $('.EmployeeList');
        EmployeeList();
    });

    function EmployeeList() {

        $.ajax({
            type: "GET",
            url: `http://127.0.0.1:8000/api/view-employees`,
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            data: "",
            dataType: "json",
        }).done(function(response) {
            $(".user_total").text(`Total Users - ${response.data.length}`);
            let list = "";
            response.data.forEach(function(item, index) {
                var userRole = localStorage.getItem('userRole');
                // Conditionally add an extra table cell if userRole is 'admin'
                if(userRole === 'admin'){
                    $('.action_btn').removeClass('hidden');
                }else{
                    $('.action_btn').addClass('hidden');
                }
                const extraCell = userRole === 'admin' ? `<td>
                            <button class="btn btn-primary editBtn" data-id="${item.employee_id}" data-name="${item.name}" data-email="${item.email}" data-dob="${item.dob}" data-doj="${item.doj}">Edit</button>
                            <button class="btn btn-danger deleteBtn" data-id="${item.employee_id}">Delete</button>
                        </td>` : '';
                let formattedDob = formatDate(item.dob);
                let formattedDoj = formatDate(item.doj);
                list += `<tr>
                       <td><p class="user_names">${item.employee_id}</p></td>
                       <td>${item.name}</td>
                       <td>${item.email}</td>
                       <td>${formattedDob}</td>
                       <td>${formattedDoj}</td>
                       ${extraCell}
                       </tr>`
            });
            $(".EmployeeList tbody").html(list);

            // Check if the table is already a DataTable
            if ($.fn.DataTable.isDataTable(table)) {
                table.DataTable().clear().destroy();
            }
            // Initialize the DataTable
            table.DataTable({
                language: {
                    search: '',
                    searchPlaceholder: "Search",
                    paginate: {
                        next: `<img src="http://127.0.0.1:8000/asset/next.svg" alt="Next">`,
                        previous: `<img src="http://127.0.0.1:8000/asset/previous.svg" alt="Previous">`
                    }
                },
                dom: 'B<"view_cont"frt>ip',
                buttons: [] // Removed the CSV button
            });
        }).fail(function(httpObj, textStatus) {
            if (jqXHR.status === 422) {
                // Parse the JSON response
                var response = jqXHR.responseJSON;

                // Check if response has a message
                var message = response.message || "An error occurred";

                // Display the message in an alert
                alert("Error adding employee: " + message);
            } else if (jqXHR.status === 423) {
                alert("This call has been restricted for your role.");
                window.location.href = "/login";
            } else {
                // Handle other errors
                alert("Error adding employee: " + jqXHR.statusText);
            }
        });
    }

    function formatDate(inputDate) {
        // Create a Date object from the input date string
        const date = new Date(inputDate);

        // Extract the day, month, and year
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        const year = date.getFullYear();

        // Format the date as dd-mm-yyyy
        return `${day}-${month}-${year}`;
    }

    $('#addEmployeeForm').submit(function(event) {
        event.preventDefault();
        const empId = $('#emp_id').val();
        const name = $('#name').val();
        const email = $('#email').val();
        const dob = $('#dob').val();
        const doj = $('#doj').val();

        const token = localStorage.getItem('userToken');

        $.ajax({
            type: "POST",
            url: `http://127.0.0.1:8000/api/store-employees`,
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({
                employee_id: empId,
                name: name,
                email: email,
                dob: dob,
                doj: doj
            }),
            dataType: "json",
            success: function(response) {
                alert("Employee added successfully");
                $('#addEmployeeModal').modal('hide');
                $('#emp_id').val('');
                $('#name').val('');
                $('#email').val('');
                $('#dob').val('');
                $('#doj').val('');
                EmployeeList(); // Refresh the employee list
            },
            error: function(jqXHR) {
                if (jqXHR.status === 422) {
                    // Parse the JSON response
                    var response = jqXHR.responseJSON;

                    // Check if response has a message
                    var message = response.message || "An error occurred";

                    // Display the message in an alert
                    alert("Error adding employee: " + message);
                } else if (jqXHR.status === 423) {
                    alert("This call has been restricted for your role.");
                    window.location.href = "/login";
                } else {
                    // Handle other errors
                    alert("Error adding employee: " + jqXHR.statusText);
                }
            }
        });
    });

    // Handle Edit button click
    $(document).on('click', '.editBtn', function () {
        $('#editUserId').val($(this).data('id'));
        $('#editName').val($(this).data('name'));
        $('#editEmail').val($(this).data('email'));
        $('#editDob').val($(this).data('dob'));
        $('#editDoj').val($(this).data('doj'));
        $('#editModal').modal('show');
    });

    // Handle Update button click
    $('#updateBtn').click(function () {
        const userId = $('#editUserId').val();
        const name = $('#editName').val();
        const email = $('#editEmail').val();
        const dob = $('#editDob').val();
        const doj = $('#editDoj').val();
        const token = localStorage.getItem('userToken');

        $.ajax({
            type: "PUT",
            url: `http://127.0.0.1:8000/api/employees/${userId}`,
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({
                employee_id: userId,
                name: name,
                email: email,
                dob: dob,
                doj: doj
            }),
            dataType: "json",
        }).done(function (response) {
            alert("Employee updated successfully");
            $('#editModal').modal('hide');
            EmployeeList();
        }).fail(function (httpObj, textStatus) {
            if (jqXHR.status === 422) {
                // Parse the JSON response
                var response = jqXHR.responseJSON;

                // Check if response has a message
                var message = response.message || "An error occurred";

                // Display the message in an alert
                alert("Error adding employee: " + message);
            } else if (jqXHR.status === 423) {
                alert("This call has been restricted for your role.");
                window.location.href = "/login";
            } else {
                // Handle other errors
                alert("Error adding employee: " + jqXHR.statusText);
            }
        });
    });

    // Handle Delete button click
    $(document).on('click', '.deleteBtn', function () {
        if (confirm("Are you sure you want to delete this employee?")) {
            const userId = $(this).data('id');
            const token = localStorage.getItem('userToken');

            $.ajax({
                type: "DELETE",
                url: `http://127.0.0.1:8000/api/delete-employee/${userId}`,
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                data: "",
                dataType: "json",
            }).done(function (response) {
                alert("Employee deleted successfully");
                EmployeeList();
            }).fail(function (httpObj, textStatus) {
                if (jqXHR.status === 422) {
                    // Parse the JSON response
                    var response = jqXHR.responseJSON;

                    // Check if response has a message
                    var message = response.message || "An error occurred";

                    // Display the message in an alert
                    alert("Error adding employee: " + message);
                } else if (jqXHR.status === 423) {
                    alert("This call has been restricted for your role.");
                    window.location.href = "/login";
                } else {
                    // Handle other errors
                    alert("Error adding employee: " + jqXHR.statusText);
                }
            });
        }
    });

    // Handle Logout button click
    $('#logoutBtn').click(function() {
        const token = localStorage.getItem('userToken');
        
        $.ajax({
            type: "POST",
            url: `http://127.0.0.1:8000/api/logout`,
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            data: "", // No data is required for logout
            dataType: "json",
        }).done(function(response) {
            alert("Successfully logged out");
            localStorage.removeItem('userToken'); // Clear the token from local storage
            localStorage.removeItem('userRole'); // Clear the token from local storage
            window.location.href = "/login"; // Redirect to login page
        }).fail(function(jqXHR) {
            if (jqXHR.status === 422) {
                // Parse the JSON response
                var response = jqXHR.responseJSON;

                // Check if response has a message
                var message = response.message || "An error occurred";

                // Display the message in an alert
                alert("Error adding employee: " + message);
            } else if (jqXHR.status === 423) {
                alert("This call has been restricted for your role.");
                window.location.href = "/login";
            } else {
                // Handle other errors
                alert("Error adding employee: " + jqXHR.statusText);
            }
        });
    });
    </script>
</body>


</html>

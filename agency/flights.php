<?php
require('inc/essentials.php');
require('inc/db_config.php');
agencylogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Agency Panel - Flights</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">FLIGHTS</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-primary shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-flight">
                                <i class="bi bi-plus-square"></i> Add New Flight
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover border">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Flight Name</th>
                                        <th scope="col">Departure</th>
                                        <th scope="col">Arrival</th>
                                        <th scope="col">Dep. Time</th>
                                        <th scope="col">Arr. Time</th>
                                        
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="flights-data">
                                    </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="modal fade" id="add-flight" tabindex="-1" aria-labelledby="add-flight-label" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="add_flight_form">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="add-flight-label">Add New Flight</h5>
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Flight Name</label>
                                        <input type="text" name="flight_name" class="form-control shadow-none" required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Departure City</label>
                                            <input type="text" name="departure_city" class="form-control shadow-none" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Arrival City</label>
                                            <input type="text" name="arrival_city" class="form-control shadow-none" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Departure Time</label>
                                            <input type="datetime-local" name="departure_time" class="form-control shadow-none" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Arrival Time</label>
                                            <input type="datetime-local" name="arrival_time" class="form-control shadow-none" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status</label>
                                        <select name="status" class="form-select shadow-none">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                                    <button type="submit" class="btn custom-bg text-white shadow-none">ADD</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal fade" id="edit-flight" tabindex="-1" aria-labelledby="edit-flight-label" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="edit_flight_form">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="edit-flight-label">Edit Flight</h5>
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="flight_id">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Flight Name</label>
                                        <input type="text" name="flight_name" class="form-control shadow-none" required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Departure City</label>
                                            <input type="text" name="departure_city" class="form-control shadow-none" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Arrival City</label>
                                            <input type="text" name="arrival_city" class="form-control shadow-none" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Departure Time</label>
                                            <input type="datetime-local" name="departure_time" class="form-control shadow-none" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Arrival Time</label>
                                            <input type="datetime-local" name="arrival_time" class="form-control shadow-none" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status</label>
                                        <select name="status" class="form-select shadow-none">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                                    <button type="submit" class="btn custom-bg text-white shadow-none">SAVE CHANGES</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
    <script src="scripts/flights.js"></script>

</body>
</html>
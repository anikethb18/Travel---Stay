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
    <title>Travel Agency Panel - Tours</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">TOURS</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-primary shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-tour">
                                <i class="bi bi-plus-square"></i> Add New Tour
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover border">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Tour Name</th>
                                        <th scope="col">Destination</th>
                                        <th scope="col">Duration</th>
                                        <th scope="col">Price</th>
                                        <th scope="col" width="30%">Description</th>
                                        
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tours-data">
                                    </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="modal fade" id="add-tour" tabindex="-1" aria-labelledby="add-tour-label" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form id="add_tour_form">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="add-tour-label">Add New Tour</h5>
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Tour Name</label>
                                            <input type="text" name="tour_name" class="form-control shadow-none" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Destination</label>
                                            <input type="text" name="destination" class="form-control shadow-none" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Duration</label>
                                            <input type="text" name="duration" class="form-control shadow-none">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Price</label>
                                            <input type="number" name="price" class="form-control shadow-none" min="0">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Status</label>
                                            <select name="status" class="form-select shadow-none">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Description</label>
                                        <textarea name="desc" class="form-control shadow-none" rows="3"></textarea>
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

                <div class="modal fade" id="edit-tour" tabindex="-1" aria-labelledby="edit-tour-label" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form id="edit_tour_form">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="edit-tour-label">Edit Tour</h5>
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="tour_id">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Tour Name</label>
                                            <input type="text" name="tour_name" class="form-control shadow-none" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Destination</label>
                                            <input type="text" name="destination" class="form-control shadow-none" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Duration</label>
                                            <input type="text" name="duration" class="form-control shadow-none">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Price</label>
                                            <input type="number" name="price" class="form-control shadow-none" min="0">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Status</label>
                                            <select name="status" class="form-select shadow-none">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Description</label>
                                        <textarea name="desc" class="form-control shadow-none" rows="3"></textarea>
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
    <script src="scripts/tours.js"></script>

</body>
</html>
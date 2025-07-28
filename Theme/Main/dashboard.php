
<main role="main" class="inner cover">
    <div class="container-fluid w-100" style="margin-top: 80px !important;" id="MainContent">
        <div class="row w-100">  
            <div class="col">      
                <div class="card">
                    <div class="card-body" style="text-align: left !important;">
                    <h5 class="card-title">Projects</h5>
                        <div class="container">
                            <div class="row align-items-center w-100 p-3">
                                <div class="col w-100">
                                    <div class="card h-100 w-100 d-inline-block">
                                        <div class="card-body">
                                            <h5 class="card-title">New Project</h5>
                                            <p class="card-text">Create a new Vizmo using our visual editor!</p>
                                            <span class="align-bottom"><button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#createGraphModal" id="CreateGraphButton1">Create</button></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $postquery['id'] = $_SESSION['id'];
                            echo get_projects($postquery);
                            ?>
                            <div class="row row-cols-4 align-items-center w-100 p-3">
                                <div class="col w-25">
                                    <div class="card h-100 d-inline-block">
                                        <div class="card-body">
                                            <h5 class="card-title">New Project</h5>
                                            <p class="card-text">Create a new Vizmo using our visual editor!</p>
                                            <span class="align-bottom"><button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#createGraphModal" id="CreateGraphButton2">Create</button></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col w-25">
                                    <div class="card h-100 d-inline-block">
                                        <div class="card-body">
                                            <h5 class="card-title">My Project</h5>
                                            <p class="card-text">Edit Project using our visual editor!</p>
                                            <span class="align-bottom"><a href="#" class="btn btn-block btn-primary">Open</a></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col w-25">
                                    <div class="card h-100 d-inline-block">
                                        <div class="card-body">
                                            <h5 class="card-title">My Project</h5>
                                            <p class="card-text">Edit Project using our visual editor!</p>
                                            <span class="align-bottom"><a href="#" class="btn btn-block btn-primary">Open</a></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col w-25">
                                    <div class="card h-100 d-inline-block">
                                        <div class="card-body">
                                            <h5 class="card-title">My Project</h5>
                                            <p class="card-text">Edit Project using our visual editor!</p>
                                            <span class="align-bottom"><a href="#" class="btn btn-block btn-primary">Open</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
        </div>
    </div>    
</main>    

<!-- Delete Project Confirmation Modal -->
<div class="modal fade" id="deleteProjectModal" role="dialog" aria-labelledby="deleteProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProjectModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the project "<span id="projectNameToDelete"></span>"?</p>
                <p class="text-danger"><strong>This action cannot be undone.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Project</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    console.log('Document ready, setting up delete functionality...');
    
    // Handle delete button clicks
    $(document).on('click', '.delete-project-btn', function(e) {
        e.preventDefault();
        console.log('Delete button clicked!');
        
        var projectId = $(this).data('project-id');
        var projectName = $(this).data('project-name');
        
        console.log('Project ID:', projectId, 'Project Name:', projectName);
        
        // Set the project name in the modal
        $('#projectNameToDelete').text(projectName);
        
        // Store the project ID for the confirm button
        $('#confirmDeleteBtn').data('project-id', projectId);
    });
    
    // Handle confirm delete button click
    $('#confirmDeleteBtn').on('click', function() {
        console.log('Confirm delete button clicked!');
        
        var projectId = $(this).data('project-id');
        console.log('Attempting to delete project ID:', projectId);
        
        // Show loading state
        $(this).prop('disabled', true).text('Deleting...');
        
        // Make AJAX request to delete the project
        console.log('Making AJAX request to delete project...');
        $.ajax({
            url: 'actions.php',
            type: 'POST',
            data: {
                action: 'delete_project',
                project_id: projectId
            },
            dataType: 'json',
            success: function(response) {
                console.log('AJAX Success Response:', response);
                if (response.success) {
                    // Reload the page to refresh the project list
                    location.reload();
                } else {
                    // Show error message
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.error('Response Text:', xhr.responseText);
                alert('An error occurred while deleting the project. Please try again.');
            },
            complete: function() {
                // Reset button state
                $('#confirmDeleteBtn').prop('disabled', false).text('Delete Project');
                // Close the modal
                $('#deleteProjectModal').modal('hide');
            }
        });
    });
});
</script>

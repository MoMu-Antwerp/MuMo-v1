<?php settings_header("Add user"); ?>

<div class="row row-cols-1 row-cols-md-2">
    <!-- information block -->
    <div class="col mb-4">
        <div class="card">
            <div class="card-header border-info strong">
                User information
            </div>

            <form action="parse_settings.php?new_user" method="post">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="user_name" class="col-sm-4 col-form-label">
                            <h6> User name</h6>
                        </label>
                        <div class="col-sm-8">
                            <input id="user_name" name="user_name" class="form-control" type="text"
                                placeholder="User name" required></input>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-4 col-form-label">
                            <h6> Email adress</h6>
                        </label>
                        <div class="col-sm-8">
                            <input id="email" name="email" class="form-control" type="email" placeholder="Email adress"
                                required></input>
                        </div>
                    </div>

                    <div class="custom-control custom-checkbox text-center mb-3">
                        <input type="checkbox" class="custom-control-input" id="edit" name="edit" value="true">
                        <label class="custom-control-label" for="edit">This user can make edits</label>
                    </div>

                    <input class="btn btn-primary btn-block" type="submit" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>
<?php settings_footer(); ?>
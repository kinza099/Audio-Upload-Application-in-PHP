<?php
include 'layout.php';
?>

<div class="container" style="margin-top:60px; margin-bottom:60px">
    <div class="navbar-dark bg-dark" style="height:650px">
        <div style="padding-top:40px">
            <h1 class="text-center text-white">Contact Us</h1>
        </div>
        <div>
            <p class="text-center text-white">Get in Touch With Our Team to Learn More About Our Audio Upload Application</p>
        </div>
        <div class="text-white" style="padding-left:100px; padding-right:100px; padding-top:20px;">
            <form>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="fname">First Name</label>
                        <input type="text" class="form-control" id="fname" name="fname">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="lname">Last Name</label>
                        <input type="text" class="form-control" id="lname" name="lname">
                    </div>
                </div>
                <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="email" class="form-control" id="Email" name="email">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="country">Country</label>
                        <input type="text" class="form-control" id="country" name="country">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" name="city">
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

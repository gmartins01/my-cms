<?php
require "../../utils/functions.php";
require "../../db/connection.php";
?>

<?=template_header('Salary Simulator')?>

    <div class="section">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="section-title">Salary Simulator</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <form>    
            <div class="container">
                <div class="form-group">
                
                <label for="base_salary" class="main-text">Base Salary</label>
                    <input type="number" id="base_salary" class="form-control form-control-lg" 
                        required min="0"/>

                <label for="meal_allowance_type" class="main-text">Meal Allowance</label>
                <select name="meal_allowance_type" id="meal_allowance_type" class="form-control form-control-lg" required>
                    <option value="no_allowance">No meal allowance</option>
                    <option value="card">Meal Card</option>
                    <option value="money">Money</option>
                </select>

                <label for="meal_allowance_amount" class="main-text">Meal Allowance Amount</label>
                <input
                    class="form-control form-control-lg"
                    required
                    type="number"
                    id="meal_allowance_amount"
                    min="0"
                    value="0"
                    disabled
                    >
                
                <label for="work_days" class="main-text">How many days did you work?</label>
                <input
                    class="form-control form-control-lg"
                    required
                    type="number"
                    id="work_days"
                    min="0"
                    value="0"
                    disabled
                    >

                    <div class="row text-center">
                        <div class="col-md-6">
                            <input type="reset" class="custom-button button-cancel" value="Reset">
                            
                        </div>
                        <div class="col-md-6">
                            <button id="calculate" class="custom-button button-update" 
                                type="button">Calculate</button>
                        </div>
                    </div>
                </div>    
            </div>
        </form>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="section-title">Results</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <p class="main-text">Gross salary: <span id="gross_salary"></span></p>
                    <p class="main-text">IRS tax: <span id="irs_tax"></span></p>
                    <p class="main-text">Social Security tax: <span id="ss_tax"></span></p>
                    <p class="main-text">Net salary: <span id="net_salary"></span></p>
                </div>
            </div>

        </div>
    </div>
    <script src="main.js"></script>
<?=template_footer()?>

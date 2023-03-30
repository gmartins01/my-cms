window.addEventListener("load", () => {

    let irsTable = [
        { max: 710, rate: 0 },
        { max: 1015, rate: 0.113 },
        { max: 1577, rate: 0.172 },
        { max: 2109, rate: 0.219 },
        { max: 5241, rate: 0.323 },
        { max: 11384, rate: 0.392 },
        { max: Infinity, rate: 0.438 }
    ];
    
    let mealAllowanceType = document.getElementById("meal_allowance_type");
    let mealAllowance = document.getElementById("meal_allowance_amount");
    let workDays = document.getElementById("work_days");

    
    mealAllowanceType.addEventListener("change", () => {
        if (mealAllowanceType.value === "no_allowance") {
            mealAllowance.value = 0;
            workDays.value = 0;
            mealAllowance.disabled = true;
            workDays.disabled = true;
        } else {
            mealAllowance.disabled = false;
            workDays.disabled = false;
        }
    });

    workDays.addEventListener("change", () => {
        if (workDays.value > 31) {
            workDays.value = 31;
        }else if(workDays.value < 0){
            workDays.value = 0;
        }
    });

    function getTaxRate(gross_salary, irsTable) {
        for (let salary in irsTable) {
            if (salary >= gross_salary) {
                return irsTable[salary];
            }
        }
        return 0.438;
    }
    
    function calculateGrossSalary(base_salary,meal_allowance,meal_allowance_type,work_days){
        var meal_subsidy = 0;
        var gross_salary = base_salary;
        if (meal_allowance_type === "card") {
            if(meal_allowance >= 7.63){
                gross_salary = gross_salary + (meal_allowance - 7.63) * work_days;
                meal_subsidy = meal_subsidy + 7.63 * work_days;
            }else{
                meal_subsidy = meal_subsidy + meal_allowance * work_days;
            }
        }else if(meal_allowance_type === "money") {
            if(meal_allowance>=4.77){
                gross_salary = gross_salary + (meal_allowance - 4.77) * work_days;
                meal_subsidy = meal_subsidy + 4.77 * work_days;
            }else{
                meal_subsidy = meal_subsidy + meal_allowance * work_days;
            }
        }
        return {meal_subsidy: meal_subsidy, gross_salary: gross_salary};
    }

    function calculateSalary() {
         
        let baseSalary = +document.getElementById("base_salary").value;
        let mealAllowanceType = document.getElementById("meal_allowance_type").value;
        let mealAllowance = +document.getElementById("meal_allowance_amount").value;
        const workDays = +document.getElementById("work_days").value;
        
        const salary = calculateGrossSalary(baseSalary,mealAllowance,mealAllowanceType,workDays);

        let meal_subsidy = salary.meal_subsidy;
        
        var grossSalary = salary.gross_salary;
   
        const irsRate = irsTable.find(function(row) {
            return grossSalary <= row.max;
        }).rate;

        const irs_tax = grossSalary * irsRate;
        const ss_tax = grossSalary * 0.11;
        
        const netSalary = grossSalary - irs_tax - ss_tax + meal_subsidy;
        document.getElementById("net_salary").innerHTML  = netSalary.toFixed(2);
        document.getElementById("irs_tax").innerHTML  = irs_tax.toFixed(2);
        document.getElementById("ss_tax").innerHTML  = ss_tax.toFixed(2);
        document.getElementById("gross_salary").innerHTML  = grossSalary.toFixed(2);
    }

    const calculateButton = document.getElementById("calculate");
    calculateButton.addEventListener("click", calculateSalary);
    
});

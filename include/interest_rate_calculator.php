<?php
//    Formula:  A = P (1 + r/n)^nt
// A = the future value of the investment/loan, including interest
// P = the principal investment amount (the initial deposit or loan amount)
// r = the annual interest rate (decimal)
// n = the number of times that interest is compounded per unit t
// t = the time the money is invested or borrowed for

function calculate_interest($P, $r, $n, $t) {
    return $P * (1 + $r / $n) ** ($n * $t);
}
?>
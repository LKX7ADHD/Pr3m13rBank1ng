<?php
function calculate_interest($P, $r, $n, $t) {
    return $P * (1 + $r / $n) ** ($n * $t);
}

?>
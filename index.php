<?php
function MyFunc() {
static $num_func_calls = 0;
echo "my function\n";
return ++$num_func_calls;
}
echo MyFunc();
echo MyFunc();

?>

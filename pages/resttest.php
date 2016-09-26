<?php
/**
 * Test Page for the various rest tests
 */
?>
<script>
function create_test() {
	var rand = Math.random()*101;
	var obj = { rand: rand, test: 'Test Object' };
	test_create(obj,'#results-div');

}

function put_test( id ) {
	var obj = {ID: id, rand: 'Updated', test: 'Updated Test Object' };
	test_update(obj,'#results-div');

}

</script>
<h2>Tests</h2>
<button onclick="test_valid_options('#results-div')">test_valid_options</button>
<button onclick="test_blogname('#results-div')">test_blogname</button>
<button onclick="test_admin_email('#results-div')">test_admin_email</button>
<button onclick="create_test();">create_test</button>
<button onclick="get_rest_test('#results-div')">get_rest_test</button>
<button onclick="delete_rest_test(1,'#results-div')">delete_rest_test 1</button>
<button onclick="delete_rest_test(2,'#results-div')">delete_rest_test 2</button>
<button onclick="delete_rest_test(3,'#results-div')">delete_rest_test 3</button>
<button onclick="delete_rest_test(4,'#results-div')">delete_rest_test 4</button>
<button onclick="delete_rest_test(5,'#results-div')">delete_rest_test 5</button>

<button onclick="put_test(1);">put_test 1</button>
<button onclick="put_test(2);">put_test 2</button>
<button onclick="put_test(3);">put_test 3</button>
<button onclick="put_test(4);">put_test 4</button>
<button onclick="put_test(5);">put_test 5</button>


<h3>Test Results</h3>
<div id="results-div">
</div>

$j(".sidebar ul li").on('click', function () {
    $j(".sidebar ul li.active").removeClass('active');
    $j(this).addClass('active');
});

$j('.open-btn').on('click', function () {
    $j('.sidebar').addClass('active');

});


$j('.close-btn').on('click', function () {
    $j('.sidebar').removeClass('active');

})
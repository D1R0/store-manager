const navbarHandler = {
    init: function(){
        const smallComponent = $j('#smallComponent');
        const largeComponent = $j('#largeComponent');
        const toggleButton = $j('#toggleButton');
        console.log(toggleButton)
        // Add click event listener to the toggle button
        toggleButton.on('click', function() {
            // Toggle the 'hidden' class to show/hide the components
            smallComponent.toggle('hidden');
            largeComponent.toggle('hidden');
        });
    }
}
$j(document).ready(function(){
    navbarHandler.init()
})
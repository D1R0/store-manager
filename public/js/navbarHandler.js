const navbarHandler = {
    init: function(){
        const smallComponent = $j('#smallComponent');
        const largeComponent = $j('#largeComponent');
        const toggleButton = $j('#toggleButton');
        toggleButton.on('click', function() {
            smallComponent.toggle('hidden');
            largeComponent.toggle('hidden');
        });
    }
}
$j(document).ready(function(){
    navbarHandler.init()
})
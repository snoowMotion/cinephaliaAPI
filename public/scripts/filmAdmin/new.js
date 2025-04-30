$(document).ready(function () {
    const form = $('form[name="film"]');
    const fileInput = $('input[type="file"][name="film[afficheUrl]"]');
    const submitButton = form.find('button[type="submit"]');

    form.on('submit', function (event) {
        event.preventDefault();

        const file = fileInput[0].files[0];
        if (file) {
            const formData = new FormData();
            formData.append('file', file);

            $.ajax({
                url: '/upload',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    console.log(data);
                    if (data.fileName) {
                        const afficheUrlInput = $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'film[afficheUrl]')
                            .val(data.fileName);
                        form.append(afficheUrlInput);
                        form.remove(fileInput)

                        form.off('submit').submit();
                    } else {
                        alert('File upload failed');
                    }
                },
                error: function (error) {
                    alert('File upload failed');
                    console.log(error)
                }
            });
        } else {
            form.off('submit').submit();
        }
    });
});
$(document).ready(function () {
    get_screenshot();
});


function get_screenshot() {
    let form_data = {
        [csrfName]: csrfHash,
    };
    $.ajax({
        url: base_url + 'screenshot/get_system_settings',
        type: "POST",
        data: form_data,
        beforeSend: function () { },
        success: function (result) {
            var res = JSON.parse(result);
            csrfName = res.csrfName;
            csrfHash = res.csrfHash;
            let video
            let captureInterval
            document.getElementById('startCaptureBtn').addEventListener('click', () => {
                startCapture()
            })
            document.getElementById('stopCaptureBtn').addEventListener('click', () => {
                stopCapture()
            })
            async function startCapture() {
                try {
                    const stream = await navigator.mediaDevices.getDisplayMedia({
                        video: {
                            mediaSource: 'screen'
                        }
                    })
                    video = document.createElement('video')
                    video.srcObject = stream
                    video.onloadedmetadata = () => {
                        const canvas = document.getElementById('canvas')
                        canvas.width = video.videoWidth
                        canvas.height = video.videoHeight
                        captureInterval = setInterval(() => {
                            captureAndUpload(canvas)
                        }, res.data)
                    }
                    video.play()
                    document.getElementById('startCaptureBtn').style.display = 'none'
                    document.getElementById('stopCaptureBtn').style.display = 'block'
                } catch (error) {
                    console.error('Error starting screen capture:', error)
                }
            }

            function stopCapture() {
                clearInterval(captureInterval)
                document.getElementById('startCaptureBtn').style.display = 'block'
                document.getElementById('stopCaptureBtn').style.display = 'none'
            }

            function captureAndUpload(canvas) {
                const context = canvas.getContext('2d')
                context.drawImage(video, 0, 0, canvas.width, canvas.height)
                // Convert canvas to base64 data URL
                const imageDataUrl = canvas.toDataURL('image/png')
                // Upload the image data to the server
                uploadScreenshot(imageDataUrl)
            }

            function uploadScreenshot(imageDataUrl) {
                var formData = new FormData();
                formData.append('csrfName', csrfHash);
                formData.append('image', imageDataUrl);

                // Make AJAX request
                $.ajax({
                    type: 'POST',
                    url: base_url + 'screenshot/upload',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        csrfName = response.csrfName;
                        csrfHash = response.csrfHash;
                    },
                    error: function (error) {
                        console.error('Error uploading image:', error);
                    }
                    
                });
            }
        }
    });
}

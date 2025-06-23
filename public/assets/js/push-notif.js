navigator.serviceWorker.register('service-worker.js');
    
function askForPermission() {
    console.log("Meminta izin notifikasi...");
    Notification.requestPermission().then((permission) => {
        console.log("Permission result:", permission);

        if (permission === 'granted') {
            console.log("Izin diberikan. Menunggu service worker siap...");

            navigator.serviceWorker.ready.then((sw) => {
                console.log("Service worker siap. Mendaftarkan subscription...");

                sw.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: "BKb-3rQok_QrP9KkjbWxRubtdVGbvXqWY2DNFN89oCLf4fB_0w4aLm2tP0QFi4T9PLQoTxdcPOG0pYEppywW-KA"
                }).then((subscription) => {
                    console.log("Subscription berhasil:", subscription);
                    alert("Subscription berhasil");

                    // Simpan ke server
                    saveSub(JSON.stringify(subscription));
                }).catch((err) => {
                    console.error("Gagal subscribe:", err);
                    alert("Gagal subscribe: " + err.message);
                });
            }).catch((err) => {
                console.error("Service worker tidak siap:", err);
                alert("Service worker tidak siap: " + err.message);
            });
        } else {
            console.warn("Izin notifikasi ditolak.");
            alert("Izin notifikasi ditolak.");
        }
    });
}

function saveSub(sub) {
    console.log("Menyimpan subscription ke server...", sub);
    alert("Mengirim subscription ke server...");

    const url = 'save-push-notification-sub';
    alert("URL yang digunakan: " + url);
    console.log("URL yang digunakan:", url);

    if (typeof $ === 'undefined') {
        console.error("jQuery is not loaded!");
        alert("jQuery is not loaded!");

        return;
    }
    $.ajax({
        type: 'post',
        url: url,
        data: {
            '_token': "{{ csrf_token() }}",
            'sub': sub
        },
        success: function(data) {
            console.log("Subscription berhasil disimpan ke server:", data);
            alert("Berhasil disimpan ke server!");
        },
        error: function(xhr, status, error) {
        console.error("Gagal menyimpan ke server:", {
            status: xhr.status,
            statusText: xhr.statusText,
            responseText: xhr.responseText,
            error: error
        });
        alert("Gagal menyimpan ke server: " + xhr.responseText + xhr.status);
    }
    });
}
        function sendNotification() {
            event.preventDefault();

            $.ajax({
                type: 'post',
                url: 'send-push-notification',
                data: {
                    '_token': "{{ csrf_token() }}",
                    'title': $("#title").val(),
                    'body': $("#body").val(),
                    'idOfProduct': $("#idOfProduct").val(),
                },
                success: function(data) {
                    alert('send Successfull');
                    console.log(data);
                }
            });
        }

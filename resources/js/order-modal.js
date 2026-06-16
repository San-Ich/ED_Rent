
window.calculateTotalCost = function () {
    const pickupDateElement = document.getElementById("bookPickupDate");
    const returnDateElement = document.getElementById("bookReturnDate");

    if (!pickupDateElement || !returnDateElement) return;

    const pickupInput = pickupDateElement.value;
    const returnInput = returnDateElement.value;

    const hargaHarianInput = document.getElementById("hargaHarian");
    if (!hargaHarianInput) return;

    const hargaHarian = parseFloat(hargaHarianInput.value);

    const summaryDays = document.getElementById("summaryDays");
    const summaryBaseCost = document.getElementById("summaryBaseCost");
    const summaryDeliveryCost = document.getElementById("summaryDeliveryCost");
    const summaryAddonCost = document.getElementById("summaryAddonCost");
    const summaryTotalCost = document.getElementById("summaryTotalCost");
    const durationAlert = document.getElementById("durationAlert");
    const submitBtn = document.getElementById("submitBtn");

    const deliveryMethod = document.getElementById("bookDeliveryMethod")?.value;
    const addressWrapper = document.getElementById("deliveryAddressWrapper");
    let deliveryFee = 0;

    if (deliveryMethod === "delivery") {
        addressWrapper?.classList.remove("d-none");
        deliveryFee = 75000;
    } else {
        addressWrapper?.classList.add("d-none");
    }

    if (!pickupInput || !returnInput) {
        if (submitBtn) submitBtn.disabled = true;
        return;
    }

    const pickupDate = new Date(pickupInput);
    const returnDate = new Date(returnInput);
    const timeDiff = returnDate.getTime() - pickupDate.getTime();

    if (timeDiff <= 0) {
        if (durationAlert) {
            durationAlert.innerText =
                "Tanggal kembali harus setelah tanggal ambil!";
            durationAlert.className = "text-danger small d-block mt-1 fw-bold";
        }
        if (submitBtn) submitBtn.disabled = true;

        if (summaryDays) summaryDays.innerText = "0";
        if (summaryBaseCost) summaryBaseCost.innerText = "Rp 0";
        if (summaryDeliveryCost) summaryDeliveryCost.innerText = "Rp 0";
        if (summaryAddonCost) summaryAddonCost.innerText = "Rp 0";
        if (summaryTotalCost) summaryTotalCost.innerText = "Rp 0";
        return;
    }

    let totalDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
    if (totalDays === 0) totalDays = 1;

    if (durationAlert) {
        durationAlert.innerText = `Durasi sewa valid: Berjalan selama ${totalDays} Hari`;
        durationAlert.className = "text-dark small d-block mt-1 fw-medium"; // Tema Monochrome style
    }
    if (submitBtn) submitBtn.disabled = false;

    let addonFeePerHari = 0;
    const perlengkapanTerpilih = document.querySelectorAll(
        ".addon-checkbox:checked",
    );
    perlengkapanTerpilih.forEach(function (checkbox) {
        addonFeePerHari += parseFloat(checkbox.getAttribute("data-harga")) || 0;
    });

    const totalBaseCost = hargaHarian * totalDays;
    const totalAddonCost = addonFeePerHari * totalDays;
    const finalTotal = totalBaseCost + deliveryFee + totalAddonCost;

    if (summaryDays) summaryDays.innerText = totalDays;
    if (summaryBaseCost)
        summaryBaseCost.innerText =
            "Rp " + totalBaseCost.toLocaleString("id-ID");
    if (summaryDeliveryCost)
        summaryDeliveryCost.innerText =
            "Rp " + deliveryFee.toLocaleString("id-ID");
    if (summaryAddonCost)
        summaryAddonCost.innerText =
            "Rp " + totalAddonCost.toLocaleString("id-ID");
    if (summaryTotalCost)
        summaryTotalCost.innerText = "Rp " + finalTotal.toLocaleString("id-ID");

    const hiddenTotalCost = document.getElementById("hiddenTotalCost");
    if (hiddenTotalCost) hiddenTotalCost.value = finalTotal;

    const textareaAddress = document.querySelector(
        "textarea[name='alamat_pengantaran']",
    );
    const hiddenDeliveryAddress = document.getElementById(
        "hiddenDeliveryAddress",
    );
    if (textareaAddress && hiddenDeliveryAddress) {
        hiddenDeliveryAddress.value = textareaAddress.value;
    }
};


window.tampilkanDetailBooking = function (rental) {
    
    document.getElementById("modalBookingCode").innerText = rental.kode_booking;
    document.getElementById("modalMotorName").innerText =
        rental.motor?.model || "Nama Motor";

    const imgElement = document.getElementById("modalMotorImg");
    if (imgElement) {
        imgElement.src = rental.motor?.image
            ? "/storage/" + rental.motor.image
            : "/images/default-motor.png";
    }

    const opsiTanggal = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    };
    const tglMulai = new Date(rental.tanggal_mulai).toLocaleDateString(
        "id-ID",
        opsiTanggal,
    );
    const tglKembali = new Date(
        rental.tanggal_rencana_kembali,
    ).toLocaleDateString("id-ID", opsiTanggal);

    document.getElementById("modalPickupTime").innerHTML =
        `<i class="bi bi-clock me-1 text-dark"></i> ${tglMulai}`;
    document.getElementById("modalReturnTime").innerHTML =
        `<i class="bi bi-clock me-1 text-dark"></i> ${tglKembali}`;


    const hargaHarian = rental.motor?.harga_per_hari || 0;
    const timeDiff =
        new Date(rental.tanggal_rencana_kembali) -
        new Date(rental.tanggal_mulai);
    let totalDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) || 1;

    document.getElementById("modalBreakdownBase").innerText =
        "Rp " + (hargaHarian * totalDays).toLocaleString("id-ID");
    document.getElementById("modalTotalPay").innerText =
        "Rp " + parseFloat(rental.total_harga).toLocaleString("id-ID");


    const wadahPerlengkapan = document.getElementById(
        "modalPerlengkapanTambahan",
    );
    if (wadahPerlengkapan) {
        if (rental.perlengkapan && rental.perlengkapan.length > 0) {
            const daftarNama = rental.perlengkapan
                .map((item) => item.nama_perlengkapan)
                .join(", ");
            wadahPerlengkapan.innerText = daftarNama;
        } else {
            wadahPerlengkapan.innerText = "Tidak Ada";
        }
    }


    const headerBg = document.getElementById("modalHeaderBg");
    const titleElement = document.getElementById("modalTitleMotor");

    if (headerBg && titleElement) {
        
        titleElement.innerHTML = `Detail Transaksi <span class="badge ms-2" id="statusBadge" style="font-size: 0.7rem; font-weight: 600; padding: 4px 10px; border-radius: 50px;"></span>`;
        const badge = document.getElementById("statusBadge");

        if (rental.status === "Selesai") {
            headerBg.style.background = "#ffffff";
            headerBg.style.borderBottom = "1px solid #e2e8f0";
            headerBg.classList.remove("text-white");
            headerBg.classList.add("text-dark");
            badge.style.background = "#000000";
            badge.style.color = "#ffffff";
            badge.innerText = "SELESAI";
        } else if (rental.status === "active" || rental.status === "Disewa") {
            headerBg.style.background = "#18181b"; 
            headerBg.style.borderBottom = "none";
            headerBg.classList.remove("text-dark");
            headerBg.classList.add("text-white");
            badge.style.background = "#ffffff";
            badge.style.color = "#000000";
            badge.innerText = "DISEWA";
        } else if (
            rental.status === "Batal" ||
            rental.status === "failed" ||
            rental.status === "Gagal"
        ) {
            headerBg.style.background = "#ffffff";
            headerBg.style.borderBottom = "1px solid #f4f4f5";
            headerBg.classList.remove("text-white");
            headerBg.classList.add("text-dark");
            badge.style.background = "#ef4444"; 
            badge.style.color = "#ffffff";
            badge.innerText = "BATAL";
        } else {
            headerBg.style.background = "#0f172a"; 
            headerBg.style.borderBottom = "none";
            headerBg.classList.remove("text-dark");
            headerBg.classList.add("text-white");
            badge.style.background = "#f4f4f5";
            badge.style.color = "#000000";
            badge.innerText = "MENUNGGU";
        }
    }

    const wadahTombolAksi = document.getElementById("modalMainAction");
    if (wadahTombolAksi) {
        let htmlTombol = "";

        const btnDarkStyle = `background: #000000; color: #ffffff; border: 1px solid #000000; transition: all 0.2s ease; font-weight: 600; font-size: 0.9rem; padding: 12px;`;
        const btnLightStyle = `background: #ffffff; color: #000000; border: 1px solid #e4e4e7; transition: all 0.2s ease; font-weight: 600; font-size: 0.9rem; padding: 12px;`;

        if (rental.status === "waiting" || rental.status === "Menunggu") {
            htmlTombol = `
                <a href="/orders/${rental.id}/payment" class="btn rounded-pill shadow-sm custom-mono-btn" style="${btnDarkStyle}">
                    <i class="bi bi-credit-card-2-front me-2"></i>Bayar Pesanan
                </a>`;
        } else if (rental.status === "active" || rental.status === "Disewa") {
            htmlTombol = `
                <a href="/rental/${rental.id}/download-struk" class="btn rounded-pill shadow-sm custom-mono-btn" style="${btnLightStyle}">
                    <i class="bi bi-download me-2"></i>Unduh Struk Digital
                </a>`;
        } else if (rental.status === "Pending Denda") {
            htmlTombol = `
                <a href="/rental/${rental.id}/Pembayaran-Denda" class="btn rounded-pill shadow-sm text-white" style="background: #18181b; border: 1px solid #18181b; font-weight: 600; padding: 12px;">
                    <i class="bi bi-wallet2 me-2"></i>Selesaikan Denda
                </a>`;
        } else if (rental.status === "failed" || rental.status === "Gagal") {
            const urlSewa = rental.motor_id
                ? `/catalog/${rental.motor_id}`
                : "/catalog";
            htmlTombol = `
                <a href="${urlSewa}" class="btn rounded-pill shadow-sm custom-mono-btn" style="${btnDarkStyle}">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Sewa Ulang Unit
                </a>`;
        } else {
            htmlTombol = `
                <button type="button" class="btn rounded-pill fw-semibold" style="background: #f4f4f5; color: #a1a1aa; border: 1px solid #e4e4e7; padding: 12px;" disabled>
                    <i class="bi bi-check-circle-fill me-2"></i>Selesai & Diverifikasi
                </button>`;
        }

        wadahTombolAksi.innerHTML = htmlTombol;
    }

    const modalElement = document.getElementById("orderDetailModal");
    if (modalElement) {
        const myModal = new bootstrap.Modal(modalElement);
        myModal.show();
    }
};


document.addEventListener("DOMContentLoaded", function () {
    const textareaAddress = document.querySelector(
        "textarea[name='alamat_pengantaran']",
    );
    if (textareaAddress) {
        textareaAddress.addEventListener("input", function () {
            const hiddenAddress = document.getElementById(
                "hiddenDeliveryAddress",
            );
            if (hiddenAddress) hiddenAddress.value = this.value;
        });
    }

    if (typeof window.calculateTotalCost === "function") {
        window.calculateTotalCost();
    }
});

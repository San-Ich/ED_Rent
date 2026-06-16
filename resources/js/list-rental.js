let orderModalInstance = null;

window.showOrderDetail = function (
    id,
    kode,
    motorName,
    startDate,
    endDate,
    total,
    status,
    imgUrl,
    hasProof,
    motorId, 
) {
    const modalTitle = document.getElementById("modalTitleMotor");
    const modalBookingCode = document.getElementById("modalBookingCode");
    const modalMotorImg = document.getElementById("modalMotorImg");
    const modalMotorName = document.getElementById("modalMotorName");
    const modalPickupTime = document.getElementById("modalPickupTime");
    const modalReturnTime = document.getElementById("modalReturnTime");
    const modalBreakdownBase = document.getElementById("modalBreakdownBase");
    const modalTotalPay = document.getElementById("modalTotalPay");
    const modalMainAction = document.getElementById("modalMainAction");
    const modalHeader = document.getElementById("modalHeaderBg");
    const timelineIcons = document.querySelectorAll(".timeline-icon");

    
    modalHeader.className =
        "modal-header-custom d-flex justify-content-between align-items-center p-4 text-white";
    modalHeader.style.background = "none";

  
    modalBookingCode.innerText = kode;
    modalMotorName.innerText = motorName;
    modalPickupTime.innerHTML = `<i class="bi bi-clock me-1"></i> ${startDate}`;
    modalReturnTime.innerHTML = `<i class="bi bi-clock me-1"></i> ${endDate}`;
    modalBreakdownBase.innerText = total;
    modalTotalPay.innerText = total;

    modalMotorImg.src = imgUrl
        ? imgUrl
        : "https://placehold.co/400x300/f1f5f9/0f172a?text=KudaBesiRent";

  

    if (status === "Menunggu" || status === "waiting") {
        modalTitle.innerText = "Detail Transaksi: Menunggu Pembayaran";
        modalHeader.style.backgroundColor = "#0f172a"; 

        
        if (timelineIcons[0])
            timelineIcons[0].className =
                "timeline-icon text-white bg-dark d-flex align-items-center justify-content-center rounded-circle border border-dark";
        if (timelineIcons[1])
            timelineIcons[1].className =
                "timeline-icon text-muted bg-light d-flex align-items-center justify-content-center rounded-circle border border-2";

        if (!hasProof || hasProof === "0" || hasProof === 0) {
            modalMainAction.innerHTML = `
                <a href="/orders/${id}/payment" class="btn btn-dark py-2.5 rounded-pill fw-bold shadow-sm w-100 text-center d-block" style="background-color: #0f172a; border-color: #0f172a;">
                    <i class="bi bi-credit-card-2-front me-2"></i> Selesaikan Pembayaran
                </a>
            `;
        } else {
            modalMainAction.innerHTML = `
                <div class="alert alert-secondary text-center rounded-pill py-2.5 px-3 mb-0 fw-bold small border-0 w-100" style="background-color: #f1f5f9; color: #334155;">
                    <i class="bi bi-hourglass-split me-2"></i> Menunggu Verifikasi Sistem
                </div>
            `;
        }
    } else if (status === "Gagal" || status === "failed") {
        modalTitle.innerText = "Detail Transaksi: Batalkan / Kadaluwarsa";
        modalHeader.style.backgroundColor = "#64748b";

        if (timelineIcons[0])
            timelineIcons[0].className =
                "timeline-icon text-white bg-secondary d-flex align-items-center justify-content-center rounded-circle";
        if (timelineIcons[1])
            timelineIcons[1].className =
                "timeline-icon text-white bg-secondary d-flex align-items-center justify-content-center rounded-circle";

     
        const targetCatalogUrl = motorId ? `/catalog/${motorId}` : "/catalog";
        modalMainAction.innerHTML = `
            <a href="${targetCatalogUrl}" class="btn btn-outline-dark py-2.5 rounded-pill fw-bold w-100 text-center d-block">
                <i class="bi bi-arrow-counterclockwise me-2"></i> Sewa Ulang Unit Ini
            </a>
        `;
    } else if (status === "active" || status === "Disewa") {
        modalTitle.innerText = "Detail Transaksi: Sewa Aktif";
        modalHeader.style.backgroundColor = "#1e293b"; 

        if (timelineIcons[0])
            timelineIcons[0].className =
                "timeline-icon text-white bg-dark d-flex align-items-center justify-content-center rounded-circle";
        if (timelineIcons[1])
            timelineIcons[1].className =
                "timeline-icon text-white bg-dark d-flex align-items-center justify-content-center rounded-circle";

        modalMainAction.innerHTML = `
            <a href="https://wa.me/628123456789" target="_blank" class="btn btn-outline-secondary py-2.5 rounded-pill fw-bold w-100 text-center d-block" style="border-color: #cbd5e1; color: #1e293b;">
                <i class="bi bi-whatsapp me-2 text-success"></i> Hubungi Garasi (WA)
            </a>
        `;
    } else {
       
        modalTitle.innerText = "Detail Transaksi: Selesai";
        modalHeader.style.backgroundColor = "#0f172a"; 

        if (timelineIcons[0])
            timelineIcons[0].className =
                "timeline-icon text-white bg-dark d-flex align-items-center justify-content-center rounded-circle";
        if (timelineIcons[1])
            timelineIcons[1].className =
                "timeline-icon text-white bg-dark d-flex align-items-center justify-content-center rounded-circle";

        modalMainAction.innerHTML = `
            <a href="/catalog" class="btn btn-dark py-2.5 rounded-pill fw-bold shadow-sm w-100 text-center d-block" style="background-color: #0f172a;">
                <i class="bi bi-bicycle me-2"></i> Jelajahi Katalog Motor Lain
            </a>
        `;
    }

   
    const modalElement = document.getElementById("orderDetailModal");
    if (!orderModalInstance) {
        orderModalInstance = new bootstrap.Modal(modalElement);
    }
    orderModalInstance.show();
};

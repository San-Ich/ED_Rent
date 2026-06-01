
function calculateTotalCost() {
    const pickupInput = document.getElementById("bookPickupDate")?.value;
    const returnInput = document.getElementById("bookReturnDate")?.value;
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
        durationAlert.className = "text-success small d-block mt-1 fw-bold";
    }
    if (submitBtn) submitBtn.disabled = false;

    let addonFeePerHari = 0;
    const addonGlove = document.getElementById("addonGlove");
    const addonActionCam = document.getElementById("addonActionCam");

    if (addonGlove?.checked) addonFeePerHari += parseFloat(addonGlove.value);
    if (addonActionCam?.checked)
        addonFeePerHari += parseFloat(addonActionCam.value);

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
}

window.calculateTotalCost = calculateTotalCost;

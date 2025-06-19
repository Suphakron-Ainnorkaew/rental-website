document.addEventListener('DOMContentLoaded', function() {
    console.log('Rent.js Loaded'); // ตรวจสอบการโหลด
    
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const daysCountElem = document.getElementById('days-count');
    const totalPriceElem = document.getElementById('total-price');
    const pricePerDay = parseFloat(document.getElementById('price-per-day').dataset.price);

    console.log('Elements:', startDateInput, endDateInput, daysCountElem, totalPriceElem, pricePerDay);

    if (!startDateInput || !endDateInput || !daysCountElem || !totalPriceElem) {
        console.error('One or more elements not found');
        return;
    }

    function updatePriceCalculation() {
        console.log('Calculating...', startDateInput.value, endDateInput.value);
        if (startDateInput.value && endDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            
            console.log('Dates:', startDate, endDate);
            if (endDate >= startDate) {
                const diffTime = endDate - startDate;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // รวมวันเริ่มและสิ้นสุด
                const totalPrice = (diffDays * pricePerDay).toFixed(2);
                
                daysCountElem.querySelector('span:last-child').textContent = `${diffDays} วัน`;
                totalPriceElem.textContent = `฿${Number(totalPrice).toLocaleString('th-TH')}`;
            } else {
                daysCountElem.querySelector('span:last-child').textContent = '0 วัน';
                totalPriceElem.textContent = '฿0';
            }
        }
    }

    startDateInput.addEventListener('change', function() {
        console.log('Start Date Changed:', startDateInput.value);
        if (startDateInput.value) {
            const nextDay = new Date(startDateInput.value);
            nextDay.setDate(nextDay.getDate() + 1);
            const nextDayFormatted = nextDay.toISOString().split('T')[0];
            endDateInput.min = startDateInput.value;
            
            if (!endDateInput.value || new Date(endDateInput.value) < new Date(startDateInput.value)) {
                endDateInput.value = nextDayFormatted;
            }
        }
        updatePriceCalculation();
    });

    endDateInput.addEventListener('change', function() {
        console.log('End Date Changed:', endDateInput.value);
        updatePriceCalculation();
    });
});
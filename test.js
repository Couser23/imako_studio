const serverBookings = [{"booking_date":"2026-06-16","start_time":"09:00:00","end_time":"11:30:00"}];

let currentYear = 2026;
let currentMonth = 5;
let selectedDate = 16;
let selectedMonthStr = String(currentMonth + 1).padStart(2, '0');
let selectedDateStr = String(selectedDate).padStart(2, '0');
let currentDateString = `${currentYear}-${selectedMonthStr}-${selectedDateStr}`;

let todaysBookings = serverBookings.filter(b => {
    let bDate = b.booking_date;
    if (typeof bDate === 'string' && bDate.includes('T')) bDate = bDate.split('T')[0];
    if (typeof bDate === 'string' && bDate.includes(' ')) bDate = bDate.split(' ')[0];
    return bDate === currentDateString;
});

console.log("todaysBookings:", todaysBookings);

let duration = 15;
let baseSlots = ['09:00', '09:05', '09:10', '09:15', '09:30', '10:00', '11:00', '11:25', '11:30', '11:35'];

let result = baseSlots.map((time) => {
    let isFull = false;
    let slotStartHour = parseInt(time.split(':')[0], 10);
    let slotStartMin = parseInt(time.split(':')[1], 10);
    let slotStartTotal = slotStartHour * 60 + slotStartMin;
    let slotEndTotal = slotStartTotal + duration;

    for (let b of todaysBookings) {
        if (!b.start_time || !b.end_time) continue;
        
        let bStart = b.start_time.split(':');
        let bStartTotal = parseInt(bStart[0], 10) * 60 + parseInt(bStart[1], 10);
        
        let bEnd = b.end_time.split(':');
        let bEndTotal = parseInt(bEnd[0], 10) * 60 + parseInt(bEnd[1], 10);

        if (slotStartTotal < bEndTotal && slotEndTotal > bStartTotal) {
            isFull = true;
            break;
        }
    }
    return { time: time, status: isFull ? 'full' : 'available' };
});

console.log("Slots:", result);

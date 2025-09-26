const calendarDates = document.getElementById("calendar-dates");
        const monthYear = document.getElementById("month-year");
        const prevMonth = document.getElementById("prev-month");
        const nextMonth = document.getElementById("next-month");

        let currentDate = new Date();

        function renderCalendar(date) {
            const year = date.getFullYear();
            const month = date.getMonth();
            const firstDay = new Date(year, month, 1).getDay();
            const lastDate = new Date(year, month + 1, 0).getDate();

            monthYear.textContent = `${date.toLocaleString("default", {
                month: "long",
            })} ${year}`;

            calendarDates.innerHTML = "";

            for (let i = 0; i < firstDay; i++) {
                const emptyDiv = document.createElement("div");
                calendarDates.appendChild(emptyDiv);
            }

            for (let day = 1; day <= lastDate; day++) {
                const dateDiv = document.createElement("div");
                dateDiv.textContent = day;

                const today = new Date();
                if (
                    year === today.getFullYear() &&
                    month === today.getMonth() &&
                    day === today.getDate()
                ) {
                    dateDiv.classList.add("today");
                }

                calendarDates.appendChild(dateDiv);
            }
        }

        prevMonth.addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar(currentDate);
        });

        nextMonth.addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar(currentDate);
        });

        renderCalendar(currentDate);

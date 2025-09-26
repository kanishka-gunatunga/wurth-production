function updateTime() {
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, "0");
    const minutes = now.getMinutes().toString().padStart(2, "0");
    const seconds = now.getSeconds().toString().padStart(2, "0");
  
    document.getElementById("hour1").textContent = hours[0];
    document.getElementById("hour2").textContent = hours[1];
  
    document.getElementById("minute1").textContent = minutes[0];
    document.getElementById("minute2").textContent = minutes[1];
  
    document.getElementById("second1").textContent = seconds[0];
    document.getElementById("second2").textContent = seconds[1];
  }
  
  setInterval(updateTime, 1000);
  
  updateTime();
  
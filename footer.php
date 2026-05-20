        </div> <!-- .page-card -->
        <!-- page content ends -->
    </div> <!-- .main -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function formatHeaderTime(d){
            // Format as M/D/YYYY, h:mm:ss AM/PM (e.g. 12/8/2025, 4:38:36 PM)
            try{
                return d.toLocaleString('en-US', { month: 'numeric', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true });
            }catch(e){
                // Fallback
                const mm = d.getMonth()+1;
                const dd = d.getDate();
                const yyyy = d.getFullYear();
                let hh = d.getHours();
                const ampm = hh >= 12 ? 'PM' : 'AM';
                hh = hh % 12; hh = hh ? hh : 12;
                const min = String(d.getMinutes()).padStart(2,'0');
                const sec = String(d.getSeconds()).padStart(2,'0');
                return `${mm}/${dd}/${yyyy}, ${hh}:${min}:${sec} ${ampm}`;
            }
        }

        function updateHeaderClock(){
            const el = document.getElementById('header-clock');
            if(!el) return;
            const now = new Date();
            el.textContent = formatHeaderTime(now);
        }
        updateHeaderClock(); setInterval(updateHeaderClock,1000);
    </script>
</body>
</html>

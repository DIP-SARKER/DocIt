<footer class="footer">
    <div class="container footer-container">

        <div class="footer-top">
            <!-- Brand -->
            <div class="footer-item footer-brand">
                <div class="navbar-brand">
                    <i class="fas fa-file-alt"></i>
                    DocIt
                </div>
                <p>
                    An all-in-one productivity platform that helps individuals and teams manage tasks,
                    organize documents, and shorten URLs efficiently.
                </p>
            </div>

            <!-- Legal -->
            <div class="footer-links-row">
                <h4>Legal</h4>

                <a href="{{ route('terms-and-conditions') }}" class="footer-link">
                    <i class="fas fa-file-contract"></i>
                    Terms &amp; Conditions
                </a>
                <a href="{{ route('privacy-policy') }}" class="footer-link">
                    <i class="fas fa-shield-alt"></i>
                    Privacy Policy
                </a>

            </div>

            <!-- Date / Time + Buttons -->
            <div class="footer-item footer-meta">
                <div class="datetime">
                    <i class="fas fa-calendar-alt"></i>
                    <span id="weekday">Monday,</span>
                    <span id="date">February 2, 2026</span>
                </div>

                <div class="datetime">
                    <i class="fas fa-clock"></i>
                    <span id="time">02:09:25 AM</span>
                </div>

                <div class="footer-actions">
                    <a href="https://chat.openai.com" target="_blank" rel="noopener" class="btn"
                        title="Get AI overview of DocIt">
                        <i class="fas fa-robot"></i>
                    </a>
                    <a href="https://gemini.google.com" target="_blank" rel="noopener" class="btn"
                        title="Get AI overview of DocIt">
                        <i class="fas fa-gem text-accent"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom -->
        <div class="footer-bottom">
            <p style="color: var(--text-primary)">
                &copy; 2026 DocIt. All rights reserved.
            </p>

            <div class="developer-info">
                <span>
                    Designed &amp; developed by
                    <a href="https://dip.free.nf" target="_blank" rel="noopener">DIPSARKER</a>
                </span>
                <span class="sep">|</span>
                <span>
                    Contact:
                    <a href="mailto:contact.dipsdevs@gmail.com">contact.dipsdevs@gmail.com</a>
                </span>
                <span class="sep">|</span>
                <span>
                    Website:
                    <a href="https://dip.free.nf" target="_blank" rel="noopener">dip.free.nf</a>
                </span>
            </div>
        </div>

    </div>
</footer>
<script>
    (function updateFooterDateTimeBST() {
        const weekdayEl = document.getElementById("weekday");
        const dateEl = document.getElementById("date");
        const timeEl = document.getElementById("time");

        if (!weekdayEl || !dateEl || !timeEl) return;

        const optionsDate = {
            timeZone: "Asia/Dhaka",
            year: "numeric",
            month: "long",
            day: "numeric"
        };

        const optionsWeekday = {
            timeZone: "Asia/Dhaka",
            weekday: "long"
        };

        const optionsTime = {
            timeZone: "Asia/Dhaka",
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
            hour12: true
        };

        function update() {
            const now = new Date();

            weekdayEl.textContent =
                now.toLocaleDateString("en-US", optionsWeekday) + ",";

            dateEl.textContent =
                now.toLocaleDateString("en-US", optionsDate);

            timeEl.textContent =
                now.toLocaleTimeString("en-US", optionsTime);
        }

        update();
        setInterval(update, 1000);
    })();
</script>

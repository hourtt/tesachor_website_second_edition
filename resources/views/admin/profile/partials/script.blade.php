<script>
    function autoResizeInput(initialVal, padding = 36, isPassword = false, maxWidth = 360) {
        return {
            val: initialVal || '',
            width: 150,
            showPass: false,
            init() {
                this.resize();
                this.$nextTick(() => this.resize());
                setTimeout(() => this.resize(), 50);
                setTimeout(() => this.resize(), 200);
            },
            get displayText() {
                if (isPassword && !this.showPass) {
                    return '•'.repeat((this.val || '').length);
                }
                return this.val || '';
            },
            resize() {
                this.$nextTick(() => {
                    const sizer = this.$refs.sizer;
                    if (sizer) {
                        const textW = sizer.getBoundingClientRect().width;
                        const calculated = Math.max(150, Math.ceil(textW + padding));
                        this.width = Math.min(maxWidth, calculated);
                    }
                });
            }
        };
    }
</script>

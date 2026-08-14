@extends('layouts.app')

@section('content')
    @include('landing.hero')
    @include('landing.why')
    @include('landing.cta')
    @include('landing.kontak')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hero = document.querySelector('.hero');
            const img = document.querySelector('.hero-mockup-img');
            if (!hero || !img) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            if (!window.matchMedia('(pointer: fine)').matches) return;

            const MAX = 10;
            let raf = null;

            hero.addEventListener('mousemove', function(e) {
                if (raf) return;
                raf = requestAnimationFrame(function() {
                    const r = hero.getBoundingClientRect();
                    const dx = ((e.clientX - r.left) / r.width) - 0.5;
                    const dy = ((e.clientY - r.top) / r.height) - 0.5;
                    img.style.transform = `translate(${dx * MAX * 2}px, ${dy * MAX}px)`;
                    raf = null;
                });
            });

            hero.addEventListener('mouseleave', function() {
                if (raf) {
                    cancelAnimationFrame(raf);
                    raf = null;
                }
                img.style.transform = 'translate(0,0)';
            });
        });
    </script>
@endsection

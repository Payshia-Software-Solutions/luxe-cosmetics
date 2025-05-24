"use client";
import React, { useState, useEffect } from "react";
import { ArrowRight, ChevronLeft, ChevronRight } from "lucide-react";
import Link from "next/link";

// Define the type for each slide
interface Slide {
  id: number;
  image: string;
  title: string;
  titleAccent: string;
  subtitle: string;
  promotion: string;
  discount: string;
}

export default function Hero() {
  const [currentSlide, setCurrentSlide] = useState<number>(0);

  const slides: Slide[] = [
    {
      id: 1,
      image:
        "https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80",
      title: "Discover Your",
      titleAccent: "Natural Beauty",
      subtitle:
        "Elevate your skincare and makeup game with our premium, naturally-derived cosmetics — curated for glow and grace.",
      promotion: "NEW ARRIVALS",
      discount: "Up to 30% OFF",
    },
    {
      id: 2,
      image:
        "https://images.unsplash.com/photo-1596462502278-27bfdc403348?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80",
      title: "Luxury",
      titleAccent: "Skincare Collection",
      subtitle:
        "Transform your daily routine with our scientifically-formulated skincare essentials for radiant, healthy-looking skin.",
      promotion: "BESTSELLER",
      discount: "Limited Time Only",
    },
    {
      id: 3,
      image:
        "https://images.unsplash.com/photo-1571781926291-c477ebfd024b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80",
      title: "Professional",
      titleAccent: "Makeup Line",
      subtitle:
        "Create stunning looks with our high-performance makeup collection designed by professionals for everyday elegance.",
      promotion: "EXCLUSIVE",
      discount: "Buy 2 Get 1 Free",
    },
  ];

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slides.length);
    }, 5000);
    return () => clearInterval(timer);
  }, [slides.length]);

  const nextSlide = () => {
    setCurrentSlide((prev) => (prev + 1) % slides.length);
  };

  const prevSlide = () => {
    setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length);
  };

  const goToSlide = (index: number) => {
    setCurrentSlide(index);
  };

  return (
    <section className="relative h-[700px] w-full overflow-hidden">
      <div className="absolute top-6 right-6 z-30">
        <div className="bg-gradient-to-r from-pink-500 to-rose-500 text-white px-4 py-2 rounded-full shadow-lg animate-pulse">
          <span className="text-sm font-semibold">
            {slides[currentSlide].promotion}
          </span>
        </div>
      </div>

      {slides.map((slide, index) => (
        <div
          key={slide.id}
          className={`absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ${
            index === currentSlide ? "opacity-100" : "opacity-0"
          }`}
          style={{ backgroundImage: `url("${slide.image}")` }}
        >
          <div className="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent" />
        </div>
      ))}

      <button
        onClick={prevSlide}
        className="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white p-2 rounded-full transition-all duration-300 hover:scale-110"
      >
        <ChevronLeft className="h-6 w-6" />
      </button>

      <button
        onClick={nextSlide}
        className="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white p-2 rounded-full transition-all duration-300 hover:scale-110"
      >
        <ChevronRight className="h-6 w-6" />
      </button>

      <div className="relative z-10 h-full container mx-auto px-6 sm:px-8 flex items-center">
        <div className="max-w-3xl">
          <div className="mb-4">
            <span className="inline-block bg-gradient-to-r from-pink-500 to-rose-500 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
              {slides[currentSlide].discount}
            </span>
          </div>

          <h1 className="text-white text-5xl sm:text-6xl lg:text-7xl font-extrabold leading-tight drop-shadow-2xl mb-6 animate-fade-in">
            {slides[currentSlide].title}
            <span className="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-rose-400 block">
              {slides[currentSlide].titleAccent}
            </span>
          </h1>

          <p className="text-gray-100 text-lg sm:text-xl mb-8 leading-relaxed drop-shadow-lg">
            {slides[currentSlide].subtitle}
          </p>

          <div className="flex flex-col sm:flex-row gap-4">
            <Link href={`/shop`}>
              <button className="group inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white text-base font-semibold px-8 py-4 rounded-full shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer">
                <span>Shop Now</span>
                <ArrowRight className="h-5 w-5 group-hover:translate-x-1 transition-transform duration-300" />
              </button>
            </Link>
            <button className="inline-flex items-center justify-center space-x-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white text-base font-semibold px-8 py-4 rounded-full border-2 border-white/30 transition-all duration-300 hover:scale-105 cursor-pointer">
              <span>View Collection</span>
            </button>
          </div>
        </div>
      </div>

      <div className="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex space-x-3">
        {slides.map((_, index) => (
          <button
            key={index}
            onClick={() => goToSlide(index)}
            className={`w-3 h-3 rounded-full transition-all duration-300 ${
              index === currentSlide
                ? "bg-white scale-125"
                : "bg-white/50 hover:bg-white/70"
            }`}
          />
        ))}
      </div>

      <div className="absolute bottom-0 left-0 w-full h-1 bg-white/20 z-20">
        <div
          className="h-full bg-gradient-to-r from-pink-500 to-rose-500 transition-all duration-300 ease-linear"
          style={{
            width: `${((currentSlide + 1) / slides.length) * 100}%`,
          }}
        />
      </div>
    </section>
  );
}

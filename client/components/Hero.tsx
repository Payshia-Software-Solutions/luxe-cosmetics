"use client";
import React, { useState, useEffect } from "react";
import { ChevronLeft, ChevronRight } from "lucide-react";
import Link from "next/link";

// Define the type for each slide
interface Slide {
  id: number;
  image: string;
  mini_image: string;
  title: string;
  titleAccent: string;
  subtitle: string;
  promotion: string;
  discount: string;
  link: string;
}

export default function Hero() {
  const [currentSlide, setCurrentSlide] = useState<number>(0);
  const [isMobile, setIsMobile] = useState(false);

  useEffect(() => {
    const handleResize = () => {
      setIsMobile(window.innerWidth < 640); // Tailwind 'sm' breakpoint
    };

    handleResize(); // check on mount
    window.addEventListener("resize", handleResize); // update on resize
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  const slides: Slide[] = [
    {
      id: 1,
      image:
        "http://content-provider.parisbeauty.lk/web-banners/ordinary-banner.webp",
      mini_image:
        "https://www.essentials.lk/cdn/shop/files/The_Ordinary_1080_X_1080_20e68f19-0cc9-4cad-9dbb-c956e5aded8f.jpg?v=1746685161&width=600",
      title: "Discover Your",
      titleAccent: "Natural Beauty",
      subtitle:
        "Elevate your skincare and makeup game with our premium, naturally-derived cosmetics — curated for glow and grace.",
      promotion: "NEW ARRIVALS",
      discount: "Up to 30% OFF",
      link: "/products/the-ordinary-nicinamide",
    },
    {
      id: 2,
      image:
        "https://www.essentials.lk/cdn/shop/files/CeraVe_1920_X_680_Banner_cbafa554-699f-40e6-b3a2-15ec348f88e0.jpg?v=1746685296&width=1800",
      mini_image:
        "https://www.essentials.lk/cdn/shop/files/CeraVe_1080_X_1080_Banner_87dfa8de-b2b3-4326-a4df-bfcc8df14263.jpg?v=1746685297&width=600",
      title: "Luxury",
      titleAccent: "Skincare Collection",
      subtitle:
        "Transform your daily routine with our scientifically-formulated skincare essentials for radiant, healthy-looking skin.",
      promotion: "BESTSELLER",
      discount: "Limited Time Only",
      link: "/shop",
    },
    {
      id: 3,
      image:
        "https://www.essentials.lk/cdn/shop/files/Outlet_1920_X_680_8a3f4fd7-f816-4223-a06b-8c7faa501e72.jpg?v=1742915125&width=1800",
      mini_image:
        "https://www.essentials.lk/cdn/shop/files/Outlet_1080_X_1080_fbc6c218-0ad5-4562-960d-8cd5444bb08e.jpg?v=1742915125&width=600",
      title: "Professional",
      titleAccent: "Makeup Line",
      subtitle:
        "Create stunning looks with our high-performance makeup collection designed by professionals for everyday elegance.",
      promotion: "EXCLUSIVE",
      discount: "Buy 2 Get 1 Free",
      link: "/shop",
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
    <section className="relative h-[50vh] sm:h-[650px] w-full overflow-hidden">
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
            index === currentSlide
              ? "opacity-100 pointer-events-auto"
              : "opacity-0 pointer-events-none"
          }`}
          style={{
            backgroundImage: `url("${
              isMobile ? slide.mini_image : slide.image
            }")`,
          }}
        >
          <Link
            href={slide.link}
            className="w-full h-full block cursor-pointer"
          ></Link>
        </div>
      ))}

      <button
        onClick={prevSlide}
        className="hidden md:flex absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white p-2 rounded-full transition-all duration-300 hover:scale-110"
      >
        <ChevronLeft className="h-6 w-6" />
      </button>

      <button
        onClick={nextSlide}
        className="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white p-2 rounded-full transition-all duration-300 hover:scale-110"
      >
        <ChevronRight className="h-6 w-6" />
      </button>

      {/* <div className="relative z-10 h-full container mx-auto px-6 sm:px-8 flex items-center">
        <div className="max-w-3xl">
          <div className="mb-4">
            <span className="inline-block bg-gradient-to-r from-pink-500 to-rose-500 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
              {slides[currentSlide].discount}
            </span>
          </div>

          <h1 className="text-white text-4xl sm:text-5xl lg:text-7xl font-extrabold leading-tight drop-shadow-2xl mb-2 animate-fade-in">
            {slides[currentSlide].title}
            <span className="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-rose-400 block">
              {slides[currentSlide].titleAccent}
            </span>
          </h1>

          <p className="text-gray-100 text-md sm:text-md mb-8 leading-relaxed drop-shadow-lg">
            {slides[currentSlide].subtitle}
          </p>

          <div className="flex flex-row sm:flex-row gap-2 sm:gap-4 ">
            <Link href={`/shop`}>
              <button className="group inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white text-base font-semibold px-8 py-4 rounded-full shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer">
                <span>Shop Now</span>
                <ArrowRight className="h-5 w-5 group-hover:translate-x-1 transition-transform duration-300" />
              </button>
            </Link>
          </div>
        </div>
      </div> */}
      {/* <Link href={slides[currentSlide].link}>
        <div className="relative z-10 h-screen container mx-auto px-6 sm:px-8 flex items-center justify-center">
          <button className="group inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white text-base font-semibold px-8 py-4 rounded-full shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer">
            <span>Shop Now</span>
            <ArrowRight className="h-5 w-5 group-hover:translate-x-1 transition-transform duration-300" />
          </button>
        </div>
      </Link> */}

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

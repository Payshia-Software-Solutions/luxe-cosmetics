import React from "react";
import Image from "next/image";
import TitleHeader from "@/components/TitleHeader";

const brands = [
  {
    name: "The Ordinary",
    logo: "/assets/logo/ordinary-logo.png",
  },
  {
    name: "L'Oréal Paris",
    logo: "/assets/logo/loral-logo.png",
  },
  {
    name: "CeraVe",
    logo: "/assets/logo/cerave-logo.png",
  },
  {
    name: "Cetaphil",
    logo: "/assets/logo/Cetaphil_Logo_285.png",
  },
];

export default function BrandGrid() {
  return (
    <div className="relative overflow-hidden">
      {/* Background gradient */}
      <div className="absolute inset-0 bg-gradient-to-br  dark:from-gray-900 dark:via-gray-800 dark:to-gray-900"></div>

      <div className="relative container mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        {/* Header Section */}
        <TitleHeader
          title="Our Cosmetic Brands"
          description="Discover premium beauty products from trusted brands that deliver exceptional results"
        />

        {/* Brand Grid */}
        <div className="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 mt-10">
          {brands.map((brand, index) => (
            <div key={index} className="group relative">
              {/* Card Background with glassmorphism effect */}
              <div className="absolute inset-0 bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-gray-700/30 shadow-xl"></div>

              {/* Hover gradient overlay */}
              <div className="absolute inset-0 bg-gradient-to-br from-rose-400/0 via-orange-400/0 to-amber-400/0 group-hover:from-rose-400/10 group-hover:via-orange-400/10 group-hover:to-amber-400/10 rounded-2xl transition-all duration-500"></div>

              {/* Card Content */}
              <div className="relative md:p-8 flex flex-col items-center text-center transform group-hover:scale-105 transition-all duration-300">
                {/* Logo Container */}
                <div className="relative mb-6">
                  <div className="w-24 h-24 lg:w-28 lg:h-28 bg-white dark:bg-gray-100 rounded-2xl shadow-lg flex items-center justify-center p-4 group-hover:shadow-2xl transition-all duration-300">
                    <Image
                      src={brand.logo}
                      alt={brand.name}
                      className="w-full h-full object-contain"
                      width={112}
                      height={112}
                    />
                  </div>
                  {/* Floating accent */}
                  <div className="absolute -top-2 -right-2 w-6 h-6 bg-gradient-to-r from-rose-500 to-amber-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>

                {/* Brand Name */}
                <h3 className="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-transparent group-hover:bg-gradient-to-r group-hover:from-rose-600 group-hover:to-amber-600 group-hover:bg-clip-text transition-all duration-300">
                  {brand.name}
                </h3>

                {/* Subtitle */}
                <p className="text-sm text-gray-500 dark:text-gray-400 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                  Premium Beauty
                </p>
              </div>

              {/* Bottom accent line */}
              <div className="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-1 bg-gradient-to-r from-rose-500 to-amber-500 rounded-full group-hover:w-16 transition-all duration-500"></div>
            </div>
          ))}
        </div>

        {/* Call to Action */}
        {/* <div className="text-center mt-16">
          <button className="group relative px-8 py-4 bg-gradient-to-r from-rose-500 to-amber-500 text-white font-semibold rounded-2xl shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
            <span className="relative z-10">Explore All Brands</span>
            <div className="absolute inset-0 bg-gradient-to-r from-rose-600 to-amber-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </button>
        </div> */}
      </div>
    </div>
  );
}

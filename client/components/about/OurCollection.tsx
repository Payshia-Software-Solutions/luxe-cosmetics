"use client";
import React from "react";
import Image from "next/image";  // Import Image from Next.js

const OurCollection = () => {
  return (
    <section className="py-8 transform translate-y-10 transition duration-1000 ease-out">
      <div className="container mx-auto px-6">
        <h2 className="text-4xl font-bold text-center text-gray-800 mb-16 relative">
          Our Collections
          <span className="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-pink-400"></span>
        </h2>

        <div className="flex flex-col md:flex-row gap-12">
          <div className="md:w-1/2 group cursor-pointer">
            <div className="relative overflow-hidden rounded-xl mb-6">
              <Image
                src="/assets/about/productc1.jpg"
                alt="Luxe skincare collection"
                width={600} // Set the image width
                height={400} // Set the image height
                className="w-full h-96 object-cover transition duration-700 ease-in-out group-hover:scale-110"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-pink-600/70 to-transparent flex items-end">
                <h3 className="text-2xl font-bold text-white p-6">Skincare</h3>
              </div>
            </div>

            <p className="text-gray-600 text-lg font-semibold">
              Our skincare line features gentle, effective formulations designed
              specifically for young skin. From hydrating serums to
              blemish-fighting treatments, each product is crafted with natural
              ingredients that nourish and protect your skin.
            </p>
          </div>

          <div className="md:w-1/2 group cursor-pointer">
            <div className="relative overflow-hidden rounded-xl mb-6">
              <Image
                src="/assets/about/productc2.jpg"
                alt="Luxe makeup collection"
                width={600} // Set the image width
                height={400} // Set the image height
                className="w-full h-96 object-cover transition duration-700 ease-in-out group-hover:scale-110"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-pink-600/70 to-transparent flex items-end">
                <h3 className="text-2xl font-bold text-white p-6">Makeup</h3>
              </div>
            </div>
            <p className="text-gray-600 font-normal text-lg font-semibold">
              Express yourself with our range of makeup products that offer
              brilliant color and reliable performance without compromising on
              ingredients. Our formulations are designed to work with your skin,
              not against it, allowing your natural beauty to shine through.
            </p>
          </div>
        </div>
      </div>
    </section>
  );
};

export default OurCollection;

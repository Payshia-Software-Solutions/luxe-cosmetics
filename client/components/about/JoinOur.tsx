"use client";
import React from "react";
import { FaInstagram, FaFacebookF, FaTwitter, FaTiktok } from "react-icons/fa";


const JoinOur = () => {
  return (
    <section className="py-24 relative transform translate-y-10 transition duration-1000 ease-out">
      <div className="absolute inset-0 z-0">
        <img
          src="/assets/about/bg.jpg"
          alt="Community of Luxe users"
          className="w-full h-full object-cover "
        />
      </div>

      <div className="absolute opacity-50 inset-0 bg-white z-10"></div>

      <div className="container mx-auto px-6 relative z-10">
        <div className="max-w-2xl mx-auto text-center">
          <h2 className="text-4xl font-bold text-gray-800 mb-6">Join Our Community</h2>
          <div className="w-24 h-1 bg-pink-400 mx-auto mb-8"></div>

          <p className="text-gray-600 mb-12">
            Luxe isn't just a brand – it's a community of like-minded individuals who believe beauty should be honest,
            fun, and empowering. Follow us on social media to join the conversation, learn beauty tips, and be the first
            to know about new product launches.
          </p>

          <div className="flex justify-center space-x-6 mb-12">
            <a href="#" className="w-12 h-12 bg-pink-600 rounded-full flex items-center justify-center text-white transition hover:scale-110">
              <FaInstagram className="w-6 h-6" />
            </a>
            <a href="#" className="w-12 h-12 bg-pink-600 rounded-full flex items-center justify-center text-white transition hover:scale-110">
              <FaFacebookF className="w-6 h-6" />
            </a>
            <a href="#" className="w-12 h-12 bg-pink-600 rounded-full flex items-center justify-center text-white transition hover:scale-110">
              <FaTwitter className="w-6 h-6" />
            </a>
            <a href="#" className="w-12 h-12 bg-pink-600 rounded-full flex items-center justify-center text-white transition hover:scale-110">
              <FaTiktok className="w-6 h-6" /> {/* TikTok style icon */}
            </a>
          </div>

          <a
            href="#"
            className="inline-block bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 px-8 rounded-full transition-colors"
          >
            Shop Our Products
          </a>
        </div>
      </div>
    </section>
  );
};

export default JoinOur;

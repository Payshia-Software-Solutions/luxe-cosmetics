"use client"
import React from 'react';

const OurPromise = () => {
  return (
    <section 
      className="py-8 bg-pink-50   transform translate-y-10 transition duration-1000 ease-out"
    >
      <div className="container mx-auto px-6 text-center max-w-3xl">
        <h2 className="text-4xl font-bold text-gray-800 mb-6">Our Promise</h2>
        <div className="w-24 h-1 bg-pink-400 mx-auto mb-12"></div>
        
        <p className="text-gray-600 mb-6 text-lg italic">
          "Every Luxe product is 100% genuine. When we say natural, we mean it. 
          When we claim a benefit, we've tested it."
        </p>
        
        <img 
          src="/assets/about/logo.png"   
          alt="Luxe logo" 
          className="mx-auto rounded-lg border-4 border-white shadow-lg mb-12"
        />
        
        <p className="text-gray-600 mb-8">
          We create products we use ourselves every day and share with our own friends and family.
          Our growing collection is designed specifically with young skin in mind. We understand the 
          unique challenges of teenage skin – from acne concerns to finding your personal style – 
          and create products that work with your skin, not against it.
        </p>
        
        <p className="text-2xl font-light text-pink-600">
          Naturally Beautiful, Genuinely You.
        </p>
      </div>
    </section>
  );
};

export default OurPromise;

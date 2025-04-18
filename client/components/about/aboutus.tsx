"use client"
import React, { useEffect, useState } from 'react';
import { motion } from 'framer-motion';
import OurPromise from './OurPromise';
import JoinOur from './JoinOur';
import OurCollection from './OurCollection';

const AboutUsPage: React.FC = () => {
  const [sections, setSections] = useState<HTMLElement[]>([]);

  useEffect(() => {
   
    const animatableSections = Array.from(document.querySelectorAll('[data-animate="true"]')) as HTMLElement[];
    setSections(animatableSections);

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('appear');
          }
        });
      },
      { threshold: 0.1 }
    );


    animatableSections.forEach(section => {
      observer.observe(section);
    });

    return () => {
      animatableSections.forEach(section => {
        observer.unobserve(section);
      });
    };
  }, []);


  const fadeIn = {
    hidden: { opacity: 0, y: 20 },
    visible: { opacity: 1, y: 0, transition: { duration: 0.6 } },
  };

  const staggerChildren = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        staggerChildren: 0.2,
      },
    },
  };

  return (
    <div className="font-sans bg-gradient-to-b from-white to-pink-50">
      {/* Hero Section */}
      <motion.section
        initial="hidden"
        animate="visible"
        variants={staggerChildren}
        className="relative h-screen flex items-center justify-center overflow-hidden"
      >
        <div className="absolute inset-0 z-0">
          <img 
            src="/assets/about/bg.jpg" 
            alt="Beautiful cosmetics flatlay" 
            className="w-full h-full object-cover"
          />
        </div>
        <div className="absolute opacity-50 inset-0 bg-gradient-to-b from-transparent to-white z-10"></div>
        
        <div className="container mx-auto px-6 z-20 text-center">
          <motion.h1 
            variants={fadeIn}
            className="text-6xl md:text-7xl font-bold text-pink-600 mb-4"
          >
            LUXE
          </motion.h1>
          <motion.p 
            variants={fadeIn}
            className="text-xl md:text-2xl text-gray-700 mb-8 max-w-2xl mx-auto"
          >
            Beauty that's honest, natural, and made for you.
          </motion.p>
          <motion.div variants={fadeIn}>
            <a href="#story" className="inline-block animate-bounce mt-8">
              <svg className="w-10 h-10 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
              </svg>
            </a>
          </motion.div>
        </div>
      </motion.section>

      {/*  Story Section */}
      <section 
        id="story" 
        data-animate="true"
        className="py-24 opacity-0 transform translate-y-10 transition duration-1000 ease-out"
      >
        <div className="container mx-auto px-6">
          <div className="flex flex-col md:flex-row items-center">
            <div className="md:w-1/2 mb-12 md:mb-0">
              <img 
                src="/assets/about/bg2.jpg"   
                alt="Founders of Luxe" 
                className="rounded-lg shadow-xl w-full max-w-lg mx-auto"
              />
            </div>
            <div className="md:w-1/2 md:pl-12">
              <h2 className="text-4xl font-bold text-gray-800 mb-6 relative">
                Our Story
                <span className="absolute bottom-0 left-0 w-24 h-1 bg-pink-400"></span>
              </h2>
              <p className="text-gray-600 mb-6 text-lg">
                Luxe was born from a simple but powerful idea: beauty shouldn't come with compromise.
              </p>
              <p className="text-gray-600 mb-6">
                Founded in 2021 by a group of friends who were frustrated with the misleading claims and harmful 
                ingredients found in many mainstream beauty products, we set out to create something different. 
                As young consumers ourselves, we wanted cosmetics that were not only effective and affordable 
                but also honest and pure.
              </p>
              <p className="text-gray-600">
                Our journey began in a small apartment kitchen, experimenting with natural formulations and 
                testing products on ourselves (never on animals!). What started as a passion project quickly 
                blossomed into something bigger when we realized how many others shared our desire for transparent, 
                genuinely natural beauty solutions.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Values Section */}
      <section 
        data-animate="true"
        className="py-24 bg-pink-50 opacity-0 transform translate-y-10 transition duration-1000 ease-out"
      >
        <div className="container mx-auto px-6">
          <h2 className="text-4xl font-bold text-center text-gray-800 mb-16 relative">
            What Makes Us Different
            <span className="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-pink-400"></span>
          </h2>
          
          <div className="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div className="bg-white rounded-xl shadow-lg p-8 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
              <div className="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mb-6">
                <svg className="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
              <h3 className="text-xl font-semibold text-gray-800 mb-4">100% Natural Ingredients</h3>
              <p className="text-gray-600">
                We source only the highest quality natural ingredients, ensuring each product is as pure as it is effective.
              </p>
            </div>
            
            <div className="bg-white rounded-xl shadow-lg p-8 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
              <div className="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mb-6">
                <svg className="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
              </div>
              <h3 className="text-xl font-semibold text-gray-800 mb-4">Genuine Products</h3>
              <p className="text-gray-600">
                When we claim a benefit, we've tested it thoroughly. No false promises, just honest beauty solutions.
              </p>
            </div>
            
            <div className="bg-white rounded-xl shadow-lg p-8 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
              <div className="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mb-6">
                <svg className="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
              </div>
              <h3 className="text-xl font-semibold text-gray-800 mb-4">Made For Young Skin</h3>
              <p className="text-gray-600">
                Formulated specifically for teenagers and young adults, addressing the unique needs of younger skin.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Products Section */}
      <section 
        data-animate="true"
        className="pacity-0 transform translate-y-10 transition duration-1000 ease-out"
      >
        <OurCollection/>
      </section>

      {/* Promise Section */}
      <section 
        data-animate="true"
        className=" bg-pink-50 opacity-0 transform translate-y-10 transition duration-1000 ease-out"
      >
       <OurPromise/>
      </section>

      {/* Join Community Section */}
      <section 
        data-animate="true"
        className="py-24 relative opacity-0 transform translate-y-10 transition duration-1000 ease-out"
      >
      <JoinOur/>
      </section>

      <style jsx>{`
        .appear {
          opacity: 1;
          transform: translateY(0);
        }
      `}</style>
    </div>
  );
};

export default AboutUsPage;
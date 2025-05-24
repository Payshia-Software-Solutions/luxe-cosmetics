"use client";

import React, { useState, ChangeEvent, FormEvent } from "react";
import axios from "axios";
import { Mail, Phone, MapPin } from "lucide-react";

interface FormData {
  full_name: string;
  email: string;
  phone: string;
  subject: string;
  message: string;
}

interface StatusMessage {
  type: "success" | "error";
  text: string;
}

function ContactUsPage() {
  const [formData, setFormData] = useState<FormData>({
    full_name: "",
    email: "",
    phone: "",
    subject: "",
    message: "",
  });

  const [loading, setLoading] = useState<boolean>(false);
  const [statusMessage, setStatusMessage] = useState<StatusMessage | null>(
    null
  );

  const handleChange = (
    e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement>
  ) => {
    setFormData((prev) => ({
      ...prev,
      [e.target.name]: e.target.value,
    }));
  };

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    // Basic validation
    if (!formData.full_name || !formData.email || !formData.message) {
      setStatusMessage({
        type: "error",
        text: "Please fill all required fields.",
      });
      return;
    }

    try {
      setLoading(true);
      const response = await axios.post(
        `${process.env.NEXT_PUBLIC_API_URL}/contact-us`,
        formData
      );

      if (response.status === 201) {
        setStatusMessage({
          type: "success",
          text: "Message sent successfully!",
        });
        setFormData({
          full_name: "",
          email: "",
          phone: "",
          subject: "",
          message: "",
        });
      }
    } catch (error: unknown) {
      if (axios.isAxiosError(error) && error.response?.data?.error) {
        setStatusMessage({
          type: "error",
          text: error.response.data.error,
        });
      } else {
        setStatusMessage({
          type: "error",
          text: "Something went wrong. Please try again.",
        });
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <section className="py-16 bg-[#fff0e9] dark:bg-[#1e1e1e] transition-colors">
      <div className="flex flex-col md:flex-row max-w-7xl mx-auto shadow-lg rounded-lg overflow-hidden mt-6 mb-6 bg-[#fff0e9]">
        {/* Left Column */}
        <div className="bg-pink-600 text-white p-8 md:p-12 md:w-1/2">
          <h2 className="text-3xl md:text-4xl font-bold mb-2">
            Let&#39;s chat.
          </h2>
          <h3 className="text-3xl md:text-4xl font-bold mb-4">
            Tell me about your project.
          </h3>
          <p className="mb-8">Let&#39;s create something together</p>

          <div className="space-y-6">
            <div className="bg-white text-[#302d2e] rounded-md p-4 flex items-center">
              <Mail className="mr-3" />
              <span className="font-medium">info@parisbeauty.lk</span>
            </div>
            <div className="bg-white text-[#302d2e] rounded-md p-4 flex items-center">
              <Phone className="mr-3" />
              <span className="font-medium">071 2 375 455</span>
            </div>
            <div className="bg-white text-[#302d2e] rounded-md p-6">
              <div className="flex items-start">
                <MapPin className="mr-3 mt-1 flex-shrink-0" />
                <div>
                  <p className="font-medium">Our Head Office,</p>
                  <p className="font-medium">parisbeauty (PVT) LTD.</p>
                  <p>
                    135, West Tower, World Trade Center, Colombo 01, Sri Lanka
                  </p>
                </div>
              </div>
            </div>
            <div className="bg-white text-[#302d2e] rounded-md p-6">
              <div className="flex items-start">
                <MapPin className="mr-3 mt-1 flex-shrink-0" />
                <div>
                  <p className="font-medium">Operation Branch,</p>
                  <p>
                    135, West Tower, World Trade Center, Colombo 01, Sri Lanka
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Right Column - Contact Form */}
        <div className="bg-white p-8 md:p-12 md:w-1/2">
          <h2 className="text-3xl font-bold mb-6 text-gray-900">
            Send us a message
          </h2>

          <form className="space-y-4" onSubmit={handleSubmit}>
            <input
              type="text"
              name="full_name"
              placeholder="Full name*"
              value={formData.full_name}
              onChange={handleChange}
              className="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E60083]"
              required
            />

            <input
              type="email"
              name="email"
              placeholder="Email address*"
              value={formData.email}
              onChange={handleChange}
              className="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E60083]"
              required
            />

            <input
              type="tel"
              name="phone"
              placeholder="Phone Number"
              value={formData.phone}
              onChange={handleChange}
              className="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E60083]"
            />

            <input
              type="text"
              name="subject"
              placeholder="Subject"
              value={formData.subject}
              onChange={handleChange}
              className="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E60083]"
            />

            <textarea
              name="message"
              placeholder="Tell us more about *"
              rows={5}
              value={formData.message}
              onChange={handleChange}
              className="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E60083]"
              required
            ></textarea>

            <button
              type="submit"
              disabled={loading}
              className="w-full bg-pink-600 text-white py-3 px-6 rounded-md hover:bg-pink-400 transition-colors"
            >
              {loading ? "Sending..." : "Send message"}
            </button>

            {statusMessage && (
              <p
                className={`text-sm mt-3 ${
                  statusMessage.type === "success"
                    ? "text-green-600"
                    : "text-red-600"
                }`}
              >
                {statusMessage.text}
              </p>
            )}
          </form>
        </div>
      </div>
    </section>
  );
}

export default ContactUsPage;

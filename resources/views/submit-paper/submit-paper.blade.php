<x-app-layout :assets="$assets ?? []">
    <section class="bg-green-50 py-10">
        <div class="container mx-auto max-w-3xl bg-white p-6 rounded-xl shadow-lg border border-green-200">
            <h2 class="text-2xl font-bold text-green-700 mb-6 text-center">Submit Paper</h2>

            <!-- Multi-Step Form -->
            <form action="{{ route('document.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- STEP 1: BIO DATA -->
                <div id="step1">
                    <h3 class="text-lg font-semibold text-green-600 mb-4">Bio Data</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-green-700">Full Name</label>
                            <input type="text" name="name" class="w-full p-3 border border-green-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none bg-green-50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-green-700">Email</label>
                            <input type="email" name="email" class="w-full p-3 border border-green-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none bg-green-50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-green-700">Phone</label>
                            <input type="text" name="phone" class="w-full p-3 border border-green-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none bg-green-50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-green-700">Address</label>
                            <input type="text" name="address" class="w-full p-3 border border-green-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none bg-green-50">
                        </div>
                    </div>
                </div>

                <!-- STEP 2: EDUCATION -->
                <div id="step2" class="hidden">
                    <h3 class="text-lg font-semibold text-green-600 mb-4">Education</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-green-700">School</label>
                            <input type="text" name="school" class="w-full p-3 border border-green-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none bg-green-50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-green-700">Degree</label>
                            <input type="text" name="degree" class="w-full p-3 border border-green-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none bg-green-50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-green-700">Year of Graduation</label>
                            <input type="text" name="graduation_year" class="w-full p-3 border border-green-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none bg-green-50">
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-6">
                    <button type="button" id="prevBtn" onclick="showStep(1)" class="hidden bg-green-200 text-green-800 px-5 py-2 rounded-lg hover:bg-green-300 transition">Previous</button>
                    <button type="button" id="nextBtn" onclick="showStep(2)" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">Next</button>
                    <button type="submit" id="submitBtn" class="hidden bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 transition">Submit</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        function showStep(step) {
            let step1 = document.getElementById('step1');
            let step2 = document.getElementById('step2');
            let nextBtn = document.getElementById('nextBtn');
            let prevBtn = document.getElementById('prevBtn');
            let submitBtn = document.getElementById('submitBtn');

            if(step === 1){
                step1.classList.remove('hidden');
                step2.classList.add('hidden');
                prevBtn.classList.add('hidden');
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            } else {
                step1.classList.add('hidden');
                step2.classList.remove('hidden');
                prevBtn.classList.remove('hidden');
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>

<template>
  <div class="min-h-screen bg-[#d1d5db] text-[#111827] font-mono p-4 sm:p-8 bg-grid-pattern">
    <div class="max-w-6xl mx-auto">
      
      <!-- HEADER -->
      <header class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 border-b-4 border-[#111827] pb-4">
        <div>
          <h1 class="text-4xl sm:text-6xl font-black uppercase tracking-tighter leading-none mb-2">System <span class="text-[#eab308]">Core</span></h1>
          <p class="text-sm font-bold uppercase tracking-widest bg-[#111827] text-white inline-block px-2 py-1">Operational Status: Nominal</p>
        </div>
        <div class="mt-4 sm:mt-0 text-right">
          <p class="text-xs font-bold uppercase tracking-widest text-gray-500">ID: SYS-X99.4</p>
          <div class="flex items-center gap-2 mt-2 justify-end">
            <div class="w-3 h-3 bg-green-500 rounded-none border border-black animate-pulse"></div>
            <span class="text-sm font-bold uppercase">Online</span>
          </div>
        </div>
      </header>

      <!-- MAIN CONTENT GRID -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- SIDEBAR METRICS -->
        <div class="col-span-1 space-y-6">
          <div class="bg-white border-2 border-[#111827] p-4 shadow-[6px_6px_0px_0px_rgba(17,24,39,1)] hover:-translate-y-1 hover:shadow-[8px_8px_0px_0px_rgba(17,24,39,1)] transition-all">
            <h2 class="text-xs font-bold uppercase tracking-widest border-b-2 border-[#111827] pb-2 mb-4">Metric / Alpha</h2>
            <div class="text-5xl font-black tracking-tighter">84.2<span class="text-xl">%</span></div>
            <p class="text-xs text-gray-500 uppercase font-bold mt-2">Efficiency Rating</p>
          </div>

          <div class="bg-[#111827] text-white border-2 border-[#111827] p-4 shadow-[6px_6px_0px_0px_rgba(234,179,8,1)] hover:-translate-y-1 hover:shadow-[8px_8px_0px_0px_rgba(234,179,8,1)] transition-all">
            <h2 class="text-xs font-bold uppercase tracking-widest border-b-2 border-white/20 pb-2 mb-4 text-[#eab308]">Metric / Beta</h2>
            <div class="text-5xl font-black tracking-tighter">4,209</div>
            <p class="text-xs text-gray-400 uppercase font-bold mt-2">Cycles Completed</p>
          </div>
        </div>

        <!-- MAIN TERMINAL AREA -->
        <div class="col-span-1 md:col-span-2">
          <div class="bg-white border-2 border-[#111827] shadow-[6px_6px_0px_0px_rgba(17,24,39,1)] h-full flex flex-col">
            <div class="bg-[#eab308] border-b-2 border-[#111827] px-4 py-2 flex items-center justify-between">
              <span class="font-black uppercase tracking-widest text-sm">Control Interface</span>
              <div class="flex gap-2">
                <div class="w-4 h-4 bg-[#111827] border border-[#111827]"></div>
                <div class="w-4 h-4 bg-white border border-[#111827]"></div>
              </div>
            </div>
            
            <div class="p-6 flex-grow flex flex-col justify-center items-center text-center">
              <div class="w-full max-w-md">
                <div class="text-6xl font-black mb-4 select-none">
                  &#9888;
                </div>
                <h3 class="text-2xl font-black uppercase mb-4">Manual Override Engaged</h3>
                <p class="text-sm font-bold text-gray-600 mb-8 border-l-4 border-[#eab308] pl-4 text-left">
                  The system is currently running in manual configuration mode. Ensure all parameters are correctly set before initializing the sequence.
                </p>
                <button class="w-full bg-[#111827] text-white font-bold uppercase tracking-widest py-4 border-2 border-[#111827] hover:bg-[#eab308] hover:text-[#111827] transition-colors shadow-[4px_4px_0px_0px_rgba(0,0,0,0.2)] hover:shadow-none translate-x-0 hover:translate-x-1 hover:translate-y-1 active:translate-x-2 active:translate-y-2 active:shadow-none">
                  Initialize Sequence
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'App',
}
</script>

<style scoped>
/* A rough grid pattern for the background to look like a cutting mat or blueprints */
.bg-grid-pattern {
  background-size: 40px 40px;
  background-image: 
    linear-gradient(to right, rgba(0, 0, 0, 0.05) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(0, 0, 0, 0.05) 1px, transparent 1px);
}
</style>

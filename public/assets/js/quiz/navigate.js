document.addEventListener('DOMContentLoaded', () => {
  const questions = document.querySelectorAll('.question-item');
  const navButtons = document.querySelectorAll('.nav-btn');
  const prevBtn = document.querySelector('.prev-question');
  const nextBtn = document.querySelector('.next-question');
  const submitBtn = document.querySelector('.buttonSumbitQuiz');

  let currentIndex = 0;

  function showQuestion(index){
    currentIndex = index;

    // Tampilkan soal
    questions.forEach((q,i)=>q.style.display=i===index?'block':'none');

    // Update tombol navigasi
    navButtons.forEach((btn,i)=>{
      btn.classList.remove('active');
      if(i===index) btn.classList.add('active');
    });

    // Tombol prev/next & submit
    prevBtn.disabled = index===0;
    nextBtn.style.display = index===questions.length-1?'none':'inline-block';
    submitBtn.style.display = index===questions.length-1?'inline-block':'none';
  }

  // Navigasi tombol
  navButtons.forEach((btn,i)=>{
    btn.addEventListener('click', ()=>{
      showQuestion(i);
      const modal = bootstrap.Modal.getInstance(document.getElementById('navigasiQuiz'));
      if(modal) modal.hide();
    });
  });

  // Prev/Next
  prevBtn.addEventListener('click', ()=>{if(currentIndex>0) showQuestion(currentIndex-1);});
  nextBtn.addEventListener('click', ()=>{if(currentIndex<questions.length-1) showQuestion(currentIndex+1);});

  // Tandai soal sudah dijawab
  questions.forEach((q,index)=>{
    const radios = q.querySelectorAll('input[type="radio"]');
    radios.forEach(radio=>{
      const label = q.querySelector(`label[for="${radio.id}"]`);
      if(label){
        label.addEventListener('click', ()=>{
          radio.checked = true; // pastikan radio ter-check
          
          // Update tombol navigasi jadi hijau
          const navBtn = document.querySelector(`.nav-btn[data-index="${index}"]`);
          if(navBtn) navBtn.classList.add('answered'); // 🟩
        });
      }
    });
  });

  showQuestion(0);
});

// 获取侧边栏和切换按钮
var sidebar = document.getElementById("mySidebar");
var toggleButton = document.querySelector('.toggle');

// 添加点击事件监听器到切换按钮
toggleButton.onclick = function() {
    // 切换侧边栏的开和收
    sidebar.classList.toggle("close");
    
    // 可选：改变按钮的图标
    this.classList.toggle('active');
};


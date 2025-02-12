class Carousel{
    /*@private {bool}*/
    #mobile = false;
    /*@private {bool}*/
    #tab = false;
    /*@private {HTMLDivElement}*/
    #root = this.#createDiv('caroussel');
    /*@private {HTMLDivElement}*/
    #container =  this.#createDiv('carousselContainer');
    /*@private {array}*/
    #items = [HTMLDivElement];
    /*@private {number}*/
    #currentSlide = 0;
    /*@private {HTMLDivElement}*/
    #button = false;
    /*@private {object}
    * @private {number} option.SlideToScroll
    * @private {number} option.SlideVisible
    */
    #option = {
        slidesToScroll : 1,
        slidesVisible : 1,
    };
    /*
    * @param {HTMLElement} element
    * @param{objet[]} option
    * @param{number} option.slidesToScroll
    * @param{number} option.slidesVisible
    *
    * Add a carousel at the children of the HTMLElement Specified
    * */
    constructor(element,option ={}){
        this.#option = Object.assign(this.#option,option);
        let children = [].slice.call(element.children)
        this.#root.appendChild(this.#container)
        element.appendChild(this.#root)
        this.#items = children.map((child) => {
            let item = this.#createDiv('carousselItem')
            item.appendChild(child)
            this.#container.appendChild(item)
            return item
        });
        this.#style()
        this.#onWindowResize()
        window.addEventListener('resize',this.#onWindowResize.bind(this))
    }
    /*
    * Look at the screen size to see if you're on mobile.
    * */
    #onWindowResize(){
        if (window.innerWidth < 640) {
            this.#mobile = true;
            this.#tab = false;
            this.#style()
        }else if(window.innerWidth < 1024){
            this.#tab = true;
            this.#mobile = false;
            this.#style();
        }else {
            this.#mobile = false;
            this.#tab = false;
            this.#style()
        }
        if(this.getSlidesVisible() < this.#items.length && !(this.#button))
            this.#createNav()
    }
    /*
    * @return {number} this.getSlidesToScroll() || 1 if (mobile)
    * */
    getSlidesToScroll(){
        if(this.#mobile)
            return 1;
        if(this.#tab)
            return 2;
        return this.#option.slidesToScroll;
    }
    /*
    * @return {number} this.getSlidesVisible() || 1 if (mobile)
    * */
    getSlidesVisible(){
        if(this.#mobile)
            return 1;
        if(this.#tab)
            return 2;
        return this.#option.slidesVisible;
    }
    /*
    * Refresh image size
    * */
    #style(){
        let ratio = this.#items.length / this.getSlidesVisible();
        this.#container.style.width = (ratio * 100)+"%"
        this.#items.forEach((item)=> {
            item.style.width = ((100 / this.getSlidesVisible()) / ratio) + "%"
        });
        // console.log(this.#items.length);
        if(this.#items.length < this.getSlidesVisible()){
            this.#items.forEach((item)=> {
                item.style.position = "relative";
                item.style.marginLeft =  (window.innerWidth-(this.#items.length*(ratio*window.innerWidth)))/2+"px";
            });
        }else{
            this.#items.forEach((item)=> {
                item.style.marginLeft = "0";
            });
        }
    }
    /*
    * @param {string} classname
    * @param {string=""} txt
    * @return HTMLDivElement
    * */
    #createDiv(className,txt = ""){
        let div = document.createElement('div')
        div.setAttribute('class',className)
        div.appendChild(document.createTextNode(txt))
        return div;
    }
    /*
    * Create 2 buttons to move the carousel elements
    * */
    #createNav(){
        let nextButton = this.#createDiv('carousselNext',">")
        let prevButton = this.#createDiv('carousselPrev',"<")
        this.#button = true
        this.#root.appendChild(prevButton);
        this.#root.appendChild(nextButton);
        nextButton.addEventListener('click',()=>{
            this.#goToItem(this.#currentSlide + this.getSlidesToScroll());
        })
        prevButton.addEventListener('click',()=>{
            this.#goToItem(this.#currentSlide - this.getSlidesToScroll());
        })
    }
    /*
    * @param {number} index
    * Move carousel images to index
    * */
    #goToItem(index){
        if (index < 0){
            index = this.#items.length - this.getSlidesVisible();
        }else if (index >= this.#items.length || this.#items[this.#currentSlide + this.getSlidesVisible()] === undefined && index > this.#currentSlide){
            index = 0;
        }
        let translateX= index * -100/ this.#items.length
        this.#container.style.transform = 'translate3D('+ translateX +'%,0,0)';
        this.#currentSlide = index;
    }
}